import requests
import argparse

arguments=None
parser = argparse.ArgumentParser(description='this function to save data from weburl to local')
parser.add_argument("--session", help=("Session ID of examination."))
                        
args = parser.parse_args()

if args.session:
    url = 'https://exam-analyze.herokuapp.com/session/analyze/khan/?session_id=' + args.session
    payload = {'username': 'master', 'password': 'pwa@pass'}
    r = requests.post(url, data=payload)
    print r.text
else:
    print ('not found session id');