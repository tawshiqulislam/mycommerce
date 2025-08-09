import json

def remove_duplicates(text):
    parts = text.split("<br>")
    seen = set()
    result = []
    for part in parts:
        if part.strip() not in seen:
            seen.add(part.strip())
            result.append(part.strip())
    return "<br>".join(result)

def fix_descriptions(json_file):
    with open(json_file, 'r', encoding='utf-8') as file:
        data = json.load(file)

    for entry in data:
        if 'description' in entry:
            entry['description'] = remove_duplicates(entry['description'])

    with open(json_file, 'w', encoding='utf-8') as file:
        json.dump(data, file, indent=4, ensure_ascii=False)

fix_descriptions('combined/combined.json')
