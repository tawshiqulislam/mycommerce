import json

def check_empty_fields(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        data = json.load(file)

    empty_fields = []

    def check_entry(entry, parent_key='', entry_name=''):
        for key, value in entry.items():
            full_key = f"{parent_key}.{key}" if parent_key else key
            if value == "" or value is None:
                empty_fields.append((entry_name, full_key, value))
            elif isinstance(value, dict):
                check_entry(value, full_key, entry_name)
            elif isinstance(value, list):
                for idx, item in enumerate(value):
                    list_key = f"{full_key}[{idx}]"
                    if isinstance(item, dict):
                        check_entry(item, list_key, entry_name)

    for entry in data:
        entry_name = entry.get('name', 'Unnamed Entry')
        check_entry(entry, entry_name=entry_name)

    return empty_fields

if __name__ == "__main__":
    empty_fields = check_empty_fields('combined/combined.json')
    if empty_fields:
        print("Empty fields found:")
        for entry_name, field, value in empty_fields:
            print(f"Product Name: {entry_name}, Field: {field}, Value: {value}")
    else:
        print("No empty fields found.")
