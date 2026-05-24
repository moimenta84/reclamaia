"""
Vehicle damage valuation service.
Abstracts DAT Ibérica, GT Estimate, Audatex/Solera behind a unified interface.
Configure which provider to use via DAMAGE_VALUATION_PROVIDER env var.

Provider env vars:
  DAT:     DAT_API_KEY, DAT_API_URL (default: https://api.dat.de/rest/...)
  AUDATEX: AUDATEX_USERNAME, AUDATEX_PASSWORD, AUDATEX_URL
  GT:      GT_API_KEY, GT_API_URL
  MOCK:    Always returns a realistic mock (for dev/testing)
"""
import os
import httpx
import json
from datetime import datetime

PROVIDER = os.environ.get("DAMAGE_VALUATION_PROVIDER", "mock").lower()


class DamageValuationError(Exception):
    pass


# ── DAT Ibérica ──────────────────────────────────────────────────────────────
def _valuation_dat(vehicle_data: dict, damage_data: dict) -> dict:
    api_key = os.environ.get("DAT_API_KEY", "")
    api_url = os.environ.get("DAT_API_URL", "https://api.dat.de/rest/valuation/v1")
    if not api_key:
        raise DamageValuationError("DAT_API_KEY not configured")

    payload = {
        "vehicle": {
            "vin":          vehicle_data.get("vin", ""),
            "licensePlate": vehicle_data.get("plate", ""),
            "make":         vehicle_data.get("make", ""),
            "model":        vehicle_data.get("model", ""),
            "year":         vehicle_data.get("year", 2020),
            "mileage":      vehicle_data.get("mileage", 50000),
        },
        "damage": {
            "description": damage_data.get("description", ""),
            "parts":       damage_data.get("parts", []),
        },
        "country": "ES",
        "currency": "EUR",
    }

    with httpx.Client(timeout=30) as client:
        r = client.post(
            f"{api_url}/estimate",
            json=payload,
            headers={"Authorization": f"Bearer {api_key}", "Content-Type": "application/json"},
        )
        r.raise_for_status()
        data = r.json()

    return {
        "provider":          "dat",
        "repair_cost_eur":   data.get("totalRepairCost", 0),
        "market_value_eur":  data.get("vehicleMarketValue", 0),
        "total_loss":        data.get("totalLoss", False),
        "parts_detail":      data.get("parts", []),
        "report_id":         data.get("reportId", ""),
        "generated_at":      datetime.now().isoformat(),
    }


# ── Audatex / Solera ─────────────────────────────────────────────────────────
def _valuation_audatex(vehicle_data: dict, damage_data: dict) -> dict:
    username = os.environ.get("AUDATEX_USERNAME", "")
    password = os.environ.get("AUDATEX_PASSWORD", "")
    api_url  = os.environ.get("AUDATEX_URL", "https://services.audatex.es/api/v2")
    if not username:
        raise DamageValuationError("AUDATEX_USERNAME not configured")

    with httpx.Client(timeout=30) as client:
        # Authenticate
        auth_r = client.post(f"{api_url}/auth/token",
                             data={"username": username, "password": password})
        auth_r.raise_for_status()
        token = auth_r.json()["access_token"]

        # Create valuation task
        task_r = client.post(
            f"{api_url}/tasks",
            json={
                "vehicle":  vehicle_data,
                "damage":   damage_data,
                "country":  "ES",
                "language": "es",
            },
            headers={"Authorization": f"Bearer {token}"},
        )
        task_r.raise_for_status()
        task = task_r.json()

    return {
        "provider":         "audatex",
        "repair_cost_eur":  task.get("repairCost", 0),
        "market_value_eur": task.get("vehicleValue", 0),
        "total_loss":       task.get("totalLoss", False),
        "task_id":          task.get("taskId", ""),
        "pdf_url":          task.get("reportUrl", ""),
        "generated_at":     datetime.now().isoformat(),
    }


# ── GT Estimate ───────────────────────────────────────────────────────────────
def _valuation_gt(vehicle_data: dict, damage_data: dict) -> dict:
    api_key = os.environ.get("GT_API_KEY", "")
    api_url = os.environ.get("GT_API_URL", "https://api.gtestimate.es/v1")
    if not api_key:
        raise DamageValuationError("GT_API_KEY not configured")

    with httpx.Client(timeout=30) as client:
        r = client.post(
            f"{api_url}/estimate",
            json={"vehicle": vehicle_data, "damage": damage_data},
            headers={"X-Api-Key": api_key},
        )
        r.raise_for_status()
        data = r.json()

    return {
        "provider":         "gt_estimate",
        "repair_cost_eur":  data.get("cost", 0),
        "market_value_eur": data.get("vehicleValue", 0),
        "total_loss":       data.get("isTotal", False),
        "estimate_id":      data.get("id", ""),
        "generated_at":     datetime.now().isoformat(),
    }


# ── Mock (dev / no API key) ───────────────────────────────────────────────────
def _valuation_mock(vehicle_data: dict, damage_data: dict) -> dict:
    import random
    repair = round(random.uniform(800, 8000), 2)
    market = round(random.uniform(12000, 35000), 2)
    return {
        "provider":         "mock",
        "repair_cost_eur":  repair,
        "market_value_eur": market,
        "total_loss":       repair > market * 0.75,
        "parts_detail": [
            {"part": "Parachoques delantero", "cost": round(repair * 0.3, 2)},
            {"part": "Capó",                  "cost": round(repair * 0.4, 2)},
            {"part": "Mano de obra",          "cost": round(repair * 0.3, 2)},
        ],
        "report_id":    "MOCK-00001",
        "generated_at": datetime.now().isoformat(),
        "warning":      "Este es un resultado de prueba. Configura DAT_API_KEY, AUDATEX_USERNAME o GT_API_KEY.",
    }


# ── Public API ────────────────────────────────────────────────────────────────
def get_damage_valuation(vehicle_data: dict, damage_data: dict) -> dict:
    """
    Request a damage valuation from the configured provider.

    vehicle_data: {vin, plate, make, model, year, mileage}
    damage_data:  {description, parts: [{name, damaged: bool}]}
    """
    providers = {
        "dat":     _valuation_dat,
        "audatex": _valuation_audatex,
        "gt":      _valuation_gt,
        "solera":  _valuation_audatex,   # Solera owns Audatex
        "mock":    _valuation_mock,
    }
    fn = providers.get(PROVIDER, _valuation_mock)
    return fn(vehicle_data, damage_data)
