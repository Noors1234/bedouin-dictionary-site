#!/bin/bash
# setup.sh — تثبيت كل متطلبات مشروع Bedouin TTS على Ubuntu

set -e

echo "=== تحديث النظام ==="
sudo apt update && sudo apt upgrade -y

echo "=== تثبيت الأدوات الأساسية ==="
sudo apt install -y git wget curl unzip ffmpeg python3 python3-venv python3-pip build-essential

echo "=== تثبيت conda (اختياري لكن موصى به) ==="
if ! command -v conda &> /dev/null; then
    wget https://repo.anaconda.com/miniconda/Miniconda3-latest-Linux-x86_64.sh -O miniconda.sh
    bash miniconda.sh -b -p $HOME/miniconda
    rm miniconda.sh
    export PATH="$HOME/miniconda/bin:$PATH"
    echo 'export PATH="$HOME/miniconda/bin:$PATH"' >> ~/.bashrc
fi

echo "=== تفعيل البيئة الافتراضية Python ==="
python3 -m venv bedouin-venv
source bedouin-venv/bin/activate
pip install --upgrade pip

echo "=== تثبيت مكتبات Python المطلوبة ==="
pip install yt-dlp openai-whisper torch torchaudio librosa soundfile numpy flask jinja2 tqdm praat-parselmouth pydub requests

echo "=== تجهيز هيكل المشروع ==="
mkdir -p bedouin-tts-pipeline/data/raw
mkdir -p bedouin-tts-pipeline/data/wav
mkdir -p bedouin-tts-pipeline/data/transcripts
mkdir -p bedouin-tts-pipeline/data/lexicon
mkdir -p bedouin-tts-pipeline/data/clips
mkdir -p bedouin-tts-pipeline/data/mfa_output
mkdir -p bedouin-tts-pipeline/web/templates
mkdir -p bedouin-tts-pipeline/scripts

echo "=== نسخ ملفات السكربتات الأساسية ==="
# هنا يُفترض أن تنسخ ملفات Python و shell من حزمة المشروع
# مثال:
# cp scripts/*.py bedouin-tts-pipeline/scripts/
# cp web/* bedouin-tts-pipeline/web/

echo "=== (اختياري) تثبيت Montreal Forced Aligner (MFA) ==="
echo "للتثبيت اليدوي: https://montreal-forced-aligner.readthedocs.io/en/latest/installation.html"

echo "=== كل شيء جاهز ==="
echo "لتشغيل واجهة الويب:"
echo "cd bedouin-tts-pipeline/web && python app.py"
echo "افتح http://localhost:5000 لتجربة النطق"
