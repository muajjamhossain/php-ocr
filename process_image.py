import easyocr
from datetime import datetime
import sys

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

current_date = datetime.now().strftime('%d-%m-%Y-%H-%M-%S')
file_name = f"{user_name}-{current_date}.txt"

with open(file_name, 'w', encoding='utf-8') as f:
    for line in text:
        f.write(line)
        f.write('\n')

print(f"Text saved to {file_name}")
