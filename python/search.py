import os

os.environ["USERNAME"] = "Pongo"
os.environ["USERPROFILE"] = r"C:\Users\Pongo"

from sentence_transformers import SentenceTransformer
from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity
import pymysql
import json
import sys

query = sys.argv[1]

model = SentenceTransformer(
    'sentence-transformers/all-MiniLM-L6-v2'
)

query_embedding = model.encode(query)

conn = pymysql.connect(
    host='127.0.0.1',
    port=3306,
    user='root',
    password='',
    database='healthself',
    charset='utf8mb4'
)

cursor = conn.cursor()

cursor.execute("""
SELECT id, question, answer, embedding
FROM health_knowledge
WHERE embedding IS NOT NULL
""")

results = []

for row in cursor.fetchall():

    id = row[0]
    question = row[1]
    answer = row[2]
    embedding = json.loads(row[3])

    score = cosine_similarity(
        [query_embedding],
        [embedding]
    )[0][0]

    results.append({
        "score": float(score),
        "question": question,
        "answer": answer
    })

results = sorted(
    results,
    key=lambda x: x["score"],
    reverse=True
)

top5 = results[:5]

context = ""

for item in top5:

    context += f"""
Question:
{item['question']}

Answer:
{item['answer']}

---------------------
"""

print(context)

cursor.close()
conn.close()