import os
from googleapiclient.discovery import build
from googleapiclient.http import MediaFileUpload
from google.oauth2 import service_account

SERVICE_JSON_ENV = os.environ.get("GOOGLE_SERVICE_ACCOUNT_JSON")
SERVICE_JSON_PATH = "service_account.json"

if not SERVICE_JSON_ENV:
    raise RuntimeError("لم يتم العثور على متغير البيئة GOOGLE_SERVICE_ACCOUNT_JSON.")

with open(SERVICE_JSON_PATH, "w", encoding="utf-8") as fh:
    fh.write(SERVICE_JSON_ENV)

SCOPES = ['https://www.googleapis.com/auth/drive.file']
credentials = service_account.Credentials.from_service_account_file(SERVICE_JSON_PATH, scopes=SCOPES)
drive_service = build('drive', 'v3', credentials=credentials)

FOLDER_ID = "18tzYP15t9Ly2cmMCTZAfcRsSdN0FOSUS"

def upload_to_drive(file_path):
    file_name = os.path.basename(file_path)
    file_metadata = {'name': file_name, 'parents': [FOLDER_ID]}
    media = MediaFileUpload(file_path, mimetype='audio/wav')
    try:
        file = drive_service.files().create(body=file_metadata, media_body=media, fields='id').execute()
        print(f"Uploaded to Drive: {file_name} (id: {file.get('id')})")
        return file.get('id')
    except Exception as e:
        print("Upload error:", e)
        return None

def main():
    audio_dir = "audio"
    if os.path.exists(audio_dir):
        for file in os.listdir(audio_dir):
            if file.lower().endswith(".wav"):
                upload_to_drive(os.path.join(audio_dir, file))
    else:
        print(f"No folder named '{audio_dir}' found.")

if __name__ == "__main__":
    main()
