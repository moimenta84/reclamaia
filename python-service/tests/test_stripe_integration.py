"""
Tests de integración con Stripe Test Mode.
Usa las claves sk_test_* reales — no llaman a producción.
Requieren conexión a internet. Marcados con @pytest.mark.stripe.
Ejecutar: pytest tests/test_stripe_integration.py -v -m stripe
"""
import os
import pytest

# Load .env from Laravel project (where the test keys live)
from pathlib import Path
env_path = Path(__file__).parent.parent.parent / "laravel" / ".env"
if env_path.exists():
    for line in env_path.read_text().splitlines():
        if "=" in line and not line.startswith("#"):
            k, _, v = line.partition("=")
            os.environ.setdefault(k.strip(), v.strip())

STRIPE_SECRET = os.environ.get("STRIPE_SECRET", "")
PRICE_ID      = os.environ.get("STRIPE_SUBSCRIPTION_PRICE_ID", "")

pytestmark = pytest.mark.stripe

# Skip entire module if no test key configured
if not STRIPE_SECRET.startswith("sk_test_"):
    pytestmark = pytest.mark.skip(reason="No Stripe test key configured")


@pytest.fixture(scope="module")
def stripe_client():
    import stripe
    stripe.api_key = STRIPE_SECRET
    return stripe


# ─── PaymentIntent ────────────────────────────────────────────────

def test_create_payment_intent(stripe_client):
    intent = stripe_client.PaymentIntent.create(
        amount=999,
        currency="eur",
        metadata={"claim_id": "test-999"},
        automatic_payment_methods={"enabled": True},
    )
    assert intent.status == "requires_payment_method"
    assert intent.amount == 999
    assert intent.currency == "eur"
    assert intent.metadata["claim_id"] == "test-999"


def test_payment_intent_has_client_secret(stripe_client):
    intent = stripe_client.PaymentIntent.create(amount=999, currency="eur")
    assert intent.client_secret is not None
    assert intent.client_secret.startswith("pi_")


def test_retrieve_payment_intent(stripe_client):
    created = stripe_client.PaymentIntent.create(amount=999, currency="eur")
    retrieved = stripe_client.PaymentIntent.retrieve(created.id)
    assert retrieved.id == created.id
    assert retrieved.amount == 999


def test_cancel_payment_intent(stripe_client):
    intent = stripe_client.PaymentIntent.create(amount=999, currency="eur")
    cancelled = stripe_client.PaymentIntent.cancel(intent.id)
    assert cancelled.status == "canceled"


# ─── Customer ────────────────────────────────────────────────────

def test_create_customer(stripe_client):
    customer = stripe_client.Customer.create(
        email="test-reclamaia@example.com",
        name="Test Asesoría SL",
    )
    assert customer.email == "test-reclamaia@example.com"
    assert customer.id.startswith("cus_")

    # Cleanup
    stripe_client.Customer.delete(customer.id)


def test_search_customer_by_email(stripe_client):
    # Stripe search index has ~5s propagation delay — only verify the API call works
    results = stripe_client.Customer.search(query="email:'search-test@reclamaia.com'")
    assert hasattr(results, "data")  # valid SearchResult object returned


# ─── Subscription price exists ───────────────────────────────────

def test_subscription_price_exists(stripe_client):
    if not PRICE_ID:
        pytest.skip("STRIPE_SUBSCRIPTION_PRICE_ID not set")
    price = stripe_client.Price.retrieve(PRICE_ID)
    assert price.id == PRICE_ID
    assert price.currency == "eur"
    assert price.active is True
