import requests
from bs4 import BeautifulSoup
import csv

URL = "https://weworkremotely.com/remote-jobs/search?term=Data+Scientist"
page = requests.get(URL)

soup = BeautifulSoup(page.content, 'html.parser')
job_list = []

jobs = soup.find_all('section', class_='jobs')
for job_section in jobs:
    job_posts = job_section.find_all('li', class_='feature')
    for post in job_posts:
        title = post.find('span', class_='title').text
        company = post.find('span', class_='company').text
        location = post.find('span', class_='region').text
        link = "https://weworkremotely.com" + post.find('a')['href']
        job_list.append([title, company, location, link])

with open('job_list.csv', mode='w', newline='', encoding='utf-8') as file:
    writer = csv.writer(file)
    writer.writerow(["Job Title", "Company", "Location", "URL"])
    writer.writerows(job_list)