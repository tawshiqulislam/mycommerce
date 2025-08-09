import argparse
import requests
from bs4 import BeautifulSoup
import json
import os
from PIL import Image, UnidentifiedImageError
from io import BytesIO

def get_data_and_image(url, partial_data_reactid_values, alternate_reactid, img_subfolder):
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    data = {}
    for key, value in partial_data_reactid_values.items():
        if key != "img" and key != "description":
            element = soup.find(attrs={"data-reactid": lambda reactid: reactid and value in reactid})
            data[key] = element.text if element else None

        elif key == "description":
            elements = soup.find_all(attrs={"data-reactid": lambda reactid: reactid and value in reactid})
            description = "\n".join([element.text for element in elements if element])
            data["description"] = description

    img_element = soup.find('img', class_='productImage')
    if img_element and 'src' in img_element.attrs:
        img_url = img_element['src']
        try:
            img_response = requests.get(img_url)
            img_data = img_response.content
            img = Image.open(BytesIO(img_data))

            if img.size != (400, 400):
                img = img.resize((400, 400))

            filename = os.path.basename(img_url).split('?')[0] + ".webp"
            filepath = os.path.join('img', img_subfolder, filename)
            os.makedirs(os.path.join('img', img_subfolder), exist_ok=True)
            img.save(filepath, 'WEBP')

            data['img'] = f"/products/img/{img_subfolder}/{filename}"
        except UnidentifiedImageError:
            data['img'] = "/products/img/none.webp"
    else:
        data['img'] = "/products/img/none.webp"

    if not data.get("price"):
        element = soup.find(attrs={"data-reactid": lambda reactid: reactid and alternate_reactid in reactid})
        data["price"] = element.text if element else None

    if not data.get("old_price"):
        element = soup.find(attrs={"data-reactid": lambda reactid: reactid and alternate_reactid in reactid})
        data["old_price"] = element.text if element else None

    return data

def read_urls_from_file(file_path):
    with open(file_path, 'r') as file:
        urls = file.readlines()
    return [url.strip() for url in urls]

def main():
    parser = argparse.ArgumentParser(description="Process URLs and extract data.")
    parser.add_argument('url_files', nargs='+', type=str, help="List of URL file paths inside 'urls' folder")
    args = parser.parse_args()

    partial_data_reactid_values = {
        "name": "e.2.0.0.0.0.0.1.0.0.1.0.0",
        "color": "e.2.0.0.0.0.0.1.0.0.1.0.1",
        "entry": "e.2.0.0.0.0.0.1.0.0.1.4.0.0",
        "price": "e.2.0.0.0.0.0.1.0.0.1.1.2.0.1.0",
        "old_price": "e.2.0.0.0.0.0.1.0.0.1.1.2.1.1",
        "description": "e.2.0.0.0.0.0.1.0.0.1.5"
    }
    alternate_reactid = "e.2.0.0.0.0.0.1.0.0.1.1.2.1.0"

    for url_file in args.url_files:
        urls = read_urls_from_file(os.path.join('urls', f"{url_file}.txt"))
        print(f"Total URLs after reading from {url_file}: {len(urls)}")
        
        data = []
        for url in urls:
            item_data = get_data_and_image(url, partial_data_reactid_values, alternate_reactid, url_file)
            data.append(item_data)
            print(f"Processed URL from {url_file}: {url}")

        os.makedirs('json', exist_ok=True)
        output_path = os.path.join('json', f"{url_file}.json")
        with open(output_path, 'w', encoding='utf-8') as json_file:
            json.dump(data, json_file, ensure_ascii=False, indent=4)

        print(f"Data extracted and saved to {output_path}")

if __name__ == "__main__":
    main()
