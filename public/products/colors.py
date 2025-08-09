import json

with open("combined/combined.json", "r", encoding="utf-8") as infile:
    data = json.load(infile)

unique_colors = {}
unique_names = set()
index = 0

for item in data:
    for variant in item.get("variants", []):
        color_name = variant.get("color", {}).get("name", "")
        if color_name and color_name not in unique_names:
            unique_colors[str(index)] = {"name": color_name}
            unique_names.add(color_name)
            index += 1

with open("colors.json", "w", encoding="utf-8") as outfile:
    json.dump(unique_colors, outfile, ensure_ascii=False, indent=4)

print("Unique colors have been written to colors.json")
