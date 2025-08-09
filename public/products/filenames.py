import os

directory = 'json'

with open('filenames.txt', 'w') as file:
    for filename in os.listdir(directory):
        if os.path.isfile(os.path.join(directory, filename)):
            file.write(filename + '\n')
