from flask import Flask, render_template
import threading
import runner_pipeline

app = Flask(__name__)

def run_pipeline():
    try:
        runner_pipeline.main()
    except Exception as e:
        print("خطأ أثناء رفع الملفات:", e)

threading.Thread(target=run_pipeline, daemon=True).start()

@app.route('/')
def index():
    return render_template('index.html')

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=10000)
