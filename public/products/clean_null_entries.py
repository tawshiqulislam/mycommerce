import json
import sys
import os

def clean_json_file(filename):
    with open(filename, 'r', encoding='utf-8') as file:
        data = json.load(file)

    cleaned_data = []
    for item in data:
        if all(value in [None, ""] for value in item.values()):
            continue
        if item.get('entry') is None:
            item['entry'] = item.get('name', '')
        cleaned_data.append(item)

    with open(filename, 'w', encoding='utf-8') as file:
        json.dump(cleaned_data, file, indent=4)

def clean_all_json_files(folder):
    for filename in os.listdir(folder):
        if filename.endswith('.json'):
            filepath = os.path.join(folder, filename)
            clean_json_file(filepath)
            print(f"Cleaned data in '{filepath}'.")

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: clean.py <folder>")
        sys.exit(1)
    
    folder = sys.argv[1]
    if not os.path.isdir(folder):
        print(f"Error: Folder '{folder}' does not exist.")
        sys.exit(1)

    clean_all_json_files(folder)
    print(f"Cleaned all JSON files in '{folder}'.")

