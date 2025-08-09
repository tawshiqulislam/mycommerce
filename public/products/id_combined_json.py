import json
import os
import sys
import re

def format_description(description):
    formatted_description = re.sub(r'\r\n|\r|\n', '<br>', description)
    formatted_description = re.sub(r'\t+', '', formatted_description)
    formatted_description = re.sub(r' +', ' ', formatted_description)
    return formatted_description

def add_unique_id_and_format_description(json_file):
    with open(json_file, 'r', encoding='utf-8') as file:
        data = json.load(file)

    variant_id = 1

    for entry in data:
        if 'description' in entry:
            entry['description'] = format_description(entry['description'])
        for variant in entry.get('variants', []):
            variant['id'] = variant_id
            variant_id += 1

    with open(json_file, 'w', encoding='utf-8') as file:
        json.dump(data, file, indent=4, ensure_ascii=False)

    print(f"Updated {json_file} with unique variant IDs and formatted descriptions.")

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python script.py <json_filename>")
        sys.exit(1)

    folder_path = 'combined'
    json_filename = sys.argv[1]
    file_path = os.path.join(folder_path, json_filename)

    if not os.path.exists(file_path):
        print(f"File '{file_path}' does not exist.")
        sys.exit(1)

    add_unique_id_and_format_description(file_path)
