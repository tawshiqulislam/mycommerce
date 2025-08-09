import os
import requests
from bs4 import BeautifulSoup
from PIL import Image
from io import BytesIO

def download_images_from_url(url, target_data_reactid):
    response = requests.get(url)
    soup = BeautifulSoup(response.content, 'html.parser')
    divs = soup.find_all(attrs={"data-reactid": lambda reactid: reactid and target_data_reactid in reactid})
    
    for div in divs:
        img = div.find('img')
        if img and 'src' in img.attrs:
            img_url = img['src']
            img_name = os.path.basename(img_url.split('?')[0])
            img_name = os.path.splitext(img_name)[0] + '.webp'
            img_path = os.path.join('categories', img_name)
            img_response = requests.get(img_url)
            image = Image.open(BytesIO(img_response.content))
            image = image.resize((400, 400))
            image.save(img_path, 'webp')

os.makedirs('categories', exist_ok=True)

with open('urls.txt', 'r') as file:
    urls = file.read().splitlines()

target_data_reactid = 'e.2.0.0.0.0.2.5.0'

for url in urls:
    download_images_from_url(url, target_data_reactid)

print("Images have been downloaded successfully from all URLs.")
