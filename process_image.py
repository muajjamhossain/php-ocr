import easyocr
from datetime import datetime
import sys
import json
import re  # Import regex for file name sanitization

# Use a default image path if no argument is provided
IMAGE_PATH = sys.argv[1] if len(sys.argv) > 1 else 'nid_2.jpg'

reader = easyocr.Reader(['en', 'bn'], gpu=True)

result = reader.readtext(IMAGE_PATH)

text = []
user_name = None

for detection in result:
    detected_text = detection[1]
    text.append(detected_text)

    if "Name" in detected_text or "নাম" in detected_text:
        try:
            user_name_index = result.index(detection) + 1
            user_name = result[user_name_index][1].strip()
        except IndexError:
            pass

user_name = user_name if user_name else "user"

# Sanitize the user_name for valid file name
def clean_file_name(name):
    return re.sub(r'[\\/*?:"<>|]', '_', name)

cleaned_user_name = clean_file_name(user_name)

current_date = datetime.now().strftime('%d-%m-%Y-%H-%M-%S')
file_name = f"{cleaned_user_name}-{current_date}.txt"

with open(file_name, 'w', encoding='utf-8') as f:
    for line in text:
        f.write(line)
        f.write('\n')

response = {
    'user_name': cleaned_user_name,
    'file_name': file_name,
    'status': 'success',
    'extracted_text': text
}
print(json.dumps(response, ensure_ascii=False))
