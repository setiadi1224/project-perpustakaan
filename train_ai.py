import pandas as pd
from sqlalchemy import create_engine
from sklearn.ensemble import IsolationForest

# 🔌 Koneksi ke database Laravel
engine = create_engine("mysql+pymysql://root:@127.0.0.1/library/perpustakaan")

# Ambil data login
query = "SELECT jam_login, ip_luar, device_baru FROM login_logs"
data = pd.read_sql(query, engine)

print("Data dari database:")
print(data.head())

# Train model
model = IsolationForest(contamination=0.2)
model.fit(data)

# Simpan model
import joblib
joblib.dump(model, "model_ai.pkl")

print("✅ Model berhasil di-train dari data real!")