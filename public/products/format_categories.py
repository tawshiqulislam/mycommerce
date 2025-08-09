import json
import datetime
import shutil
import sys
import os

def update_json(json_file, category, department):
    backup_dir = "categories.backup"
    if not os.path.exists(backup_dir):
        os.makedirs(backup_dir)
        
    backup_file = os.path.join(backup_dir, f"{json_file}.old")
    shutil.copyfile(f"categories/{json_file}", backup_file)

    with open(f"categories/{json_file}", 'r', encoding='utf-8') as file:
        data = json.load(file)

    current_time = datetime.datetime.now().strftime('%d%m%y')

    for i, entry in enumerate(data):
        entry['department'] = department
        entry['category'] = category
        entry['ref'] = f"{current_time}-{i}"
        entry['variants'] = [{
            "color": {"name": entry.pop('color', 'default_color')},
            "thumb": entry['img'],
            "img": entry.pop('img', 'default_img'),
        }]
        
    with open(f"categories/{json_file}", 'w', encoding='utf-8') as file:
        json.dump(data, file, indent=4, ensure_ascii=False)

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python format.py <category_map>")
        sys.exit(1)

    input_file = sys.argv[1]

    with open(input_file, 'r', encoding='utf-8') as file:
        input_data = json.load(file)

    for entry in input_data:
        json_file = entry['json_file']
        category = entry['category']
        department = entry['department']

        update_json(json_file, category, department)
        print(f"Updated categories/{json_file} with category '{category}' and department '{department}'. A backup has been saved as categories.backup/{json_file}.old.")
