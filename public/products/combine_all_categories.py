import argparse
import json
import os

def combine_all_json_files(output_file):
    combined_data = []
    categories_folder = 'categories'
    json_files = [f for f in os.listdir(categories_folder) if f.endswith('.json')]

    for file_name in json_files:
        file_path = os.path.join(categories_folder, file_name)
        with open(file_path, 'r', encoding='utf-8') as json_file:
            data = json.load(json_file)
            combined_data.extend(data)

    output_path = os.path.join('combined', output_file)
    with open(output_path, 'w', encoding='utf-8') as json_output:
        json.dump(combined_data, json_output, ensure_ascii=False, indent=4)

    print(f"Combined JSON saved to {output_path}")

def main():
    parser = argparse.ArgumentParser(description="Combine all JSON files in the 'categories' folder.")
    parser.add_argument('output_file', type=str, help="Output JSON file name")
    args = parser.parse_args()

    combine_all_json_files(args.output_file)

if __name__ == "__main__":
    main()
