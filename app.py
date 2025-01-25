from flask import Flask, jsonify
from googleapiclient.discovery import build
from google.oauth2 import service_account

app = Flask(__name__)

# Path to your Google service account credentials
SERVICE_ACCOUNT_FILE = '/app/google_credentials.json'
SCOPES = ['https://www.googleapis.com/auth/analytics.readonly']

# Define the `/analytics` route
@app.route('/analytics', methods=['GET'])
def get_analytics_data():
    try:
        # Authenticate and build the Google Analytics API client
        credentials = service_account.Credentials.from_service_account_file(
            SERVICE_ACCOUNT_FILE, scopes=SCOPES
        )
        analytics = build('analytics', 'v3', credentials=credentials)

        # Example: List accounts (update as per your needs)
        accounts = analytics.management().accounts().list().execute()
        return jsonify(accounts)

    except Exception as e:
        return jsonify({'error': str(e)}), 500

# Ensure the app runs when the container starts
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
