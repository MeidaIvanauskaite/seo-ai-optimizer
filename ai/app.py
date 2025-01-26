from flask import Flask, jsonify
from googleapiclient.discovery import build
from google.oauth2 import service_account
app = Flask(__name__)

SERVICE_ACCOUNT_FILE = '/app/google_credentials.json'
SCOPES = ['https://www.googleapis.com/auth/analytics.readonly']

@app.route('/analytics', methods=['GET'])
def get_analytics_data():
    try:
        credentials = service_account.Credentials.from_service_account_file(
            SERVICE_ACCOUNT_FILE, scopes=SCOPES
        )

        analytics = build('analytics', 'v3', credentials=credentials)
        accounts = analytics.management().accounts().list().execute()
        return jsonify(accounts)

    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
