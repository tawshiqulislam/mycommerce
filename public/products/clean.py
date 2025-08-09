import json

def remove_entries_with_null_name(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        data = json.load(file)

    updated_data = [entry for entry in data if entry.get('name') is not None]

    with open(file_path, 'w', encoding='utf-8') as file:
        json.dump(updated_data, file, ensure_ascii=False, indent=4)

if __name__ == "__main__":
    remove_entries_with_null_name('combined/combined.json')
