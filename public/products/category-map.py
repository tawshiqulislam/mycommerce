import json

def filename_to_category(filename):
    return ' '.join(word.capitalize() for word in filename.split('.')[0].split('-'))

with open('filenames.txt', 'r') as file:
    filenames = file.read().splitlines()

category_map = []
for filename in filenames:
    category = filename_to_category(filename)
    category_map.append({
        "json_file": filename,
        "category": category,
        "department": "Grocery"
    })
with open('category-map.json', 'w') as json_file:
    json.dump(category_map, json_file, indent=4)

print("category-map.json has been created successfully.")
