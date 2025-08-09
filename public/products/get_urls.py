from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
import argparse
import time
import os

def get_href_values(url):
    driver_path = 'chromedriver-win64/chromedriver.exe'
    service = Service(driver_path)
    op = webdriver.ChromeOptions()
    op.add_argument('headless')
    driver = webdriver.Chrome(service=service, options=op)
    driver.get(url)
    time.sleep(3)

    # Scroll to the bottom to load all elements
    last_height = driver.execute_script("return document.body.scrollHeight")
    while True:
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(2)
        new_height = driver.execute_script("return document.body.scrollHeight")
        if new_height == last_height:
            break
        last_height = new_height

    elements = driver.find_elements(By.CLASS_NAME, 'btnShowDetails')
    href_values = [element.get_attribute('href') for element in elements]
    driver.quit()
    cleaned_href_values = href_values[::2]
    return cleaned_href_values

def main():
    parser = argparse.ArgumentParser(description="Extract URLs and save to files.")
    parser.add_argument('path_file', type=str, help="File containing multiple paths after base URL")
    args = parser.parse_args()

    base_url = "https://chaldal.com/pharmacy"
    
    with open(args.path_file, 'r') as file:
        paths = file.readlines()
    
    for path in paths:
        path = path.strip()
        if not path:
            continue
        url = f"{base_url}/{path}"
        href_values = get_href_values(url)

        os.makedirs('urls', exist_ok=True)
        output_file = f"urls/{path}.txt"
        
        with open(output_file, 'w') as output:
            for href in href_values:
                output.write(f"{href}\n")

        print(f"URLs extracted and saved to {output_file}")

if __name__ == "__main__":
    main()
