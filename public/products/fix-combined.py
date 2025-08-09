import json

def update_json_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        data = json.load(file)

    for entry in data:
        for variant in entry.get('variants', []):
            if 'color' in variant and 'name' in variant['color']:
                variant['color']['name'] = 'each'
        
        if 'price' in entry and entry['price'] is not None:
            entry['price'] = entry['price'].replace(',', '')
        if 'old_price' in entry and entry['old_price'] is not None:
            entry['old_price'] = entry['old_price'].replace(',', '')

    with open(file_path, 'w', encoding='utf-8') as file:
        json.dump(data, file, ensure_ascii=False, indent=4)

if __name__ == "__main__":
    update_json_file('combined/combined.json')
