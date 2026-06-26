from sentence_transformers import SentenceTransformer
import pymysql
import json

# Model embedding
model = SentenceTransformer(
    'sentence-transformers/all-MiniLM-L6-v2'
)

# Koneksi MySQL
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
SELECT id, question, answer
FROM health_knowledge
WHERE embedding IS NULL
""")

rows = cursor.fetchall()

print(f"Found {len(rows)} records")

for row in rows:

    id = row[0]
    question = row[1]
    answer = row[2]

    text = f"""
    Question:
    {question}

    Answer:
    {answer}
    """

    embedding = model.encode(text).tolist()

    cursor.execute(
        """
        UPDATE health_knowledge
        SET embedding=%s
        WHERE id=%s
        """,
        (
            json.dumps(embedding),
            id
        )
    )

    conn.commit()

    print(f"Embedded ID {id}")

cursor.close()
conn.close()

print("DONE")