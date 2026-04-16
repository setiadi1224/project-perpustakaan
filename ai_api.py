from fastapi import FastAPI
from pydantic import BaseModel
import pandas as pd
import joblib

# 🚀 INIT APP
app = FastAPI(title="AI Login Security System")

# 🔥 LOAD MODEL
model = joblib.load("model_ai.pkl")


# 📥 REQUEST SCHEMA
class DataLogin(BaseModel):
    jam_login: int
    ip_luar: int
    device_baru: int


# 🏠 HEALTH CHECK
@app.get("/")
def home():
    return {"status": "AI aktif (cybersecurity mode 🔐)"}


# 🧠 MAIN PREDICTION ENDPOINT
@app.post("/cek")
def cek(data: DataLogin):

    # =========================
    # 1. FORMAT DATA
    # =========================
    test = pd.DataFrame([{
        "jam_login": data.jam_login,
        "ip_luar": data.ip_luar,
        "device_baru": data.device_baru
    }])

    # =========================
    # 2. ML PREDICTION
    # =========================
    pred = model.predict(test)[0]
    score = float(model.decision_function(test)[0])

    # =========================
    # 3. RULE-BASED SCORING (BOOST AI)
    # =========================
    if data.ip_luar == 1 and data.device_baru == 1:
        score -= 0.25   # sangat mencurigakan

    if data.jam_login < 5:
        score -= 0.10   # jam rawan

    # =========================
    # 4. STATUS FINAL
    # =========================
    if pred == -1 or score < -0.2:
        status = "anomali"
    else:
        status = "aman"

    # =========================
    # 5. LEVEL RISIKO
    # =========================
    if score < -0.3:
        level = "HIGH"
    elif score < -0.1:
        level = "MEDIUM"
    else:
        level = "LOW"

    # =========================
    # 6. EXPLANATION (AI REASON)
    # =========================
    reasons = []

    if data.jam_login < 5:
        reasons.append("Login di jam tidak normal")

    if data.ip_luar == 1:
        reasons.append("IP luar terdeteksi")

    if data.device_baru == 1:
        reasons.append("Device baru digunakan")

    if not reasons:
        reasons.append("Aktivitas normal terdeteksi")

    # =========================
    # 7. RESPONSE FINAL
    # =========================
    return {
        "status": status,
        "risk_score": score,
        "level": level,
        "message": "Login mencurigakan" if status == "anomali" else "Login normal",
        "reasons": reasons
    }