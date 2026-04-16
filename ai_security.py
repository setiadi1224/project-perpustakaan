import pandas as pd
from sklearn.ensemble import IsolationForest

data = pd.DataFrame({
    'jam_login': [8, 9, 10, 11, 12, 13, 14],
    'ip_luar': [0, 0, 0, 0, 0, 0, 0],
    'device_baru': [0, 0, 0, 0, 0, 0, 0]
})

model = IsolationForest(contamination=0.1)
model.fit(data)

test = pd.DataFrame({
    'jam_login': [1],
    'ip_luar': [1],
    'device_baru': [1]
})

pred = model.predict(test)
score = model.decision_function(test)

print("Prediction:", pred)
print("Score:", score)

if pred[0] == -1:
    print("⚠️ Anomali terdeteksi!")
else:
    print("Aman")