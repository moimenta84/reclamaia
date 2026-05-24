from dotenv import load_dotenv
load_dotenv()

from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware

from app.routes.generate import router as generate_router
from app.routes.analyze import router as analyze_router
from app.routes.extract_policy import router as extract_router
from app.routes.escalate import router as escalate_router
from app.routes.tools import router as tools_router
from app.routes.public_data import router as public_data_router

app = FastAPI(title="ReclamaIA Python Service", version="2.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost", "http://127.0.0.1"],
    allow_methods=["GET", "POST"],
    allow_headers=["*"],
)

app.include_router(generate_router, prefix="/api")
app.include_router(analyze_router, prefix="/api")
app.include_router(extract_router, prefix="/api")
app.include_router(escalate_router, prefix="/api")
app.include_router(tools_router, prefix="/api")
app.include_router(public_data_router, prefix="/api")


@app.get("/health")
def health():
    return {"status": "ok", "model": "claude-sonnet-4-6"}
