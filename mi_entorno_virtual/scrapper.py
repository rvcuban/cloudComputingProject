
# For webscraping
from bs4 import BeautifulSoup

import csv

# Parsing and creating xml data
from lxml import etree as et

# Store data as a csv file written out
from csv import writer

# In general to use with timing our function calls to Indeed
import time

# Assist with creating incremental timing for our scraping to seem more human
from time import sleep

# Dataframe stuff
import pandas as pd

# Random integer for more realistic timing for clicks, buttons and searches during scraping
from random import randint

# Multi Threading
import threading

# Threading:
from concurrent.futures import ThreadPoolExecutor, wait
import selenium

# Check version I am running
selenium.__version__


from selenium import webdriver

# Starting/Stopping Driver: can specify ports or location but not remote access
from selenium.webdriver.chrome.service import Service as ChromeService

# Manages Binaries needed for WebDriver without installing anything directly
from webdriver_manager.chrome import ChromeDriverManager
# Allows searchs similar to beautiful soup: find_all
from selenium.webdriver.common.by import By

# Try to establish wait times for the page to load
from selenium.webdriver.support.ui import WebDriverWait

# Wait for specific condition based on defined task: web elements, boolean are examples
from selenium.webdriver.support import expected_conditions as EC

# Used for keyboard movements, up/down, left/right,delete, etc
from selenium.webdriver.common.keys import Keys

# Locate elements on page and throw error if they do not exist
from selenium.common.exceptions import NoSuchElementException

# Selenium 4:

from selenium import webdriver

# Starting/Stopping Driver: can specify ports or location but not remote access
from selenium.webdriver.chrome.service import Service as ChromeService

# Manages Binaries needed for WebDriver without installing anything directly
from webdriver_manager.chrome import ChromeDriverManager
# Allows searchs similar to beautiful soup: find_all
from selenium.webdriver.common.by import By

# Try to establish wait times for the page to load
from selenium.webdriver.support.ui import WebDriverWait

# Wait for specific condition based on defined task: web elements, boolean are examples
from selenium.webdriver.support import expected_conditions as EC

# Used for keyboard movements, up/down, left/right,delete, etc
from selenium.webdriver.common.keys import Keys

# Locate elements on page and throw error if they do not exist
from selenium.common.exceptions import NoSuchElementException


# Allows you to cusotmize: ingonito mode, maximize window size, headless browser, disable certain features, etc
option= webdriver.ChromeOptions()

# Going undercover:
option.add_argument("--incognito")


# # Consider this if the application works and you know how it works for speed ups and rendering!

# option.add_argument('--headless=chrome')

# Define job and location search keywords
job_search_keyword = ['Data+Scientist', 'Business+Analyst', 'Data+Engineer', 
                      'Python+Developer', 'Full+Stack+Developer', 
                      'Machine+Learning+Engineer']

# Define Locations of Interest
location_search_keyword = ['New+York', 'California', 'Washington']

# Finding location, position, radius=35 miles, sort by date and starting page
paginaton_url = 'https://www.indeed.com/jobs?q={}&l={}&radius=35&filter=0&sort=date&start={}'

# print(paginaton_url)

start = time.time()


job_='Data+Engineer'
location='Washington'

driver = webdriver.Chrome(service=ChromeService(ChromeDriverManager().install()),
                         options=option)


driver.get(paginaton_url.format(job_,location,0))

# t = ScrapeThread(url_)
# t.start()

sleep(randint(2, 6))

p=driver.find_element(By.CLASS_NAME,'jobsearch-JobCountAndSortPane-jobCount').text

# Max number of pages for this search! There is a caveat described soon
max_iter_pgs=int(p.split(' ')[0])//15 


driver.quit() # Closing the browser we opened


end = time.time()

print(end - start,'seconds to complete action!')
print('-----------------------')
print('Max Iterable Pages for this search:',max_iter_pgs)

# Pagination: PRACTICE

start = time.time()


job_='Data+Engineer'
location='Washington'


job_lst=[]
job_description_list_href=[]

    # job_description_list = []
salary_list=[]


driver = webdriver.Chrome(service=ChromeService(ChromeDriverManager().install()),
                            options=option)
sleep(randint(2, 6))

    # driver.get("https://www.indeed.com/q-USA-jobs.html")

for i in range(0,max_iter_pgs):
    driver.get(paginaton_url.format(job_,location,i*10))
        
        
sleep(randint(2, 4))

job_page = driver.find_element(By.ID,"mosaic-jobResults")
jobs = job_page.find_elements(By.CLASS_NAME,"job_seen_beacon") # return a list

for jj in jobs:
            job_title = jj.find_element(By.CLASS_NAME,"jobTitle")
    #         print(job_title.text)
            
    # Href's to get full job description (need to re-terate to get full info)
    # Reference ID for each job used by indeed         
    # Finding the company name        
    # Location
    # Posting date
    # Job description

            job_lst.append([job_title.text,
            job_title.find_element(By.CSS_SELECTOR,"a").get_attribute("href"),
            job_title.find_element(By.CSS_SELECTOR,"a").get_attribute("id"),      
            job_title.find_element(By.CSS_SELECTOR,"a").get_attribute("href")])
            

            try: # I removed the metadata attached to this class name to work!
                salary_list.append(jj.find_element(By.CLASS_NAME,"salary-snippet-container").text)

            except NoSuchElementException: 
                try: 
                    salary_list.append(jj.find_element(By.CLASS_NAME,"estimated-salary").text)
                    
                except NoSuchElementException:
                    salary_list.append(None)
        
                    
    #         # Click the job element to get the description
    #         job_title.click()
            
    #         # Help to load page so we can find and extract data
    #         sleep(randint(3, 5))

    #         try: 
    #             job_description_list.append(driver.find_element(By.ID,"jobDescriptionText").text)
                
    #         except: 
                
    #             job_description_list.append(None)

driver.quit() 


end = time.time()

print(end - start,'seconds to complete Query!')

    # alternate way to grab the info for job description to make it faster:

print (job_lst[0:2])
with open('job_list.csv', mode='w', newline='', encoding='utf-8') as file:
        writer = csv.writer(file)
        writer.writerow(["Job Title", "Href", "ID", "Href Again"])  # Encabezados del CSV
        writer.writerows(job_lst)