import time, json
from selenium import webdriver
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import mysql.connector, sys, os, ast
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.remote.webelement import WebElement
import requests



requests.packages.urllib3.disable_warnings(requests.packages.urllib3.exceptions.InsecureRequestWarning)



'''while True:
    print(len(sys.argv))
    time.sleep(5)

exit()'''

def load_cookie(driver, cookie):
    cookies = json.loads(cookie)
    for cookiex in cookies:
        driver.add_cookie(cookiex)

def slow_type(element: WebElement, text: str, delay: float=0.1):
    """Send a text to an element one character at a time with a delay."""
    for character in text:
        element.send_keys(character)
        time.sleep(delay)

def alimEmirleriListesi(RequestVerificationToken, ASPNET_SessionId, ASPXAUTH):

    cookies = {
        '__RequestVerificationToken': RequestVerificationToken,
        'ASP.NET_SessionId': ASPNET_SessionId,
        '.ASPXAUTH': ASPXAUTH,
    }

    headers = {
        'Connection': 'keep-alive',
        'sec-ch-ua': '" Not A;Brand";v="99", "Chromium";v="98", "Google Chrome";v="98"',
        'Accept': 'application/json, text/javascript, */*; q=0.01',
        'DNT': '1',
        'X-Requested-With': 'XMLHttpRequest',
        'sec-ch-ua-mobile': '?0',
        'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.36',
        'sec-ch-ua-platform': '"macOS"',
        'Sec-Fetch-Site': 'same-origin',
        'Sec-Fetch-Mode': 'cors',
        'Sec-Fetch-Dest': 'empty',
        'Referer': 'https://platform.turib.com.tr/Raporlar/PiyasaEkrani',
        'Accept-Language': 'tr',
    }

    params = (
        ('_', time.time()),
    )

    response = requests.get('https://platform.turib.com.tr/Raporlar/GetPiyasaSatimEmriList', headers=headers, params=params, cookies=cookies, verify=False)
    return response.text




mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="mysql",
  database="turib"
)

mycursor = mydb.cursor()



mycursor.execute("SELECT * FROM turib_auth Where id="+str(sys.argv[1]))
myresult = mycursor.fetchall()
print(myresult)


username = myresult[0][1]
password = myresult[0][2]
user_id = myresult[0][3] 
cookies = myresult[0][6]    

chrome_options = webdriver.ChromeOptions()
chrome_options.add_argument('--ignore-certificate-errors')
chrome_options.add_argument('--ignore-ssl-errors')
#chrome_options.add_argument("--headless")

driver = webdriver.Chrome(ChromeDriverManager().install(), chrome_options=chrome_options)
#driver = webdriver.Chrome("C:\\Users\\Handan\\Downloads\\chromedriver_win32\\chromedriver.exe", chrome_options=chrome_options)
#driver.maximize_window()

if str(sys.argv[2]) == "0":
    
    driver.get("https://platform.turib.com.tr/Account/Login")

    if myresult[0][13] == "0":
        driver.find_element(By.XPATH, '//input[@name="UserName"]').send_keys(username)
        driver.find_element(By.XPATH, '//input[@name="Password"]').send_keys(password)
        driver.find_element(By.XPATH, '//input[@value="Giriş Yap"]').click()
    
    if myresult[0][13] == "2":
        driver.find_element(By.XPATH, '//ul[@id="Div_IlkGirisTab"]/li[2]').click()
        time.sleep(1)
        driver.find_element(By.XPATH, '//form[@id="SirketForm"]/div[1]/input[@name="VergiNo"]').send_keys(myresult[0][11])
        driver.find_element(By.XPATH, '//form[@id="SirketForm"]/div[2]/input[@name="Password"]').send_keys(password)
        driver.find_element(By.XPATH, '//form[@id="SirketForm"]/div[3]/input[@type="submit"]').click()

    if myresult[0][13] == "3":
        driver.find_element(By.XPATH, '//ul[@id="Div_IlkGirisTab"]/li[3]').click()
        time.sleep(1)
        driver.find_element(By.XPATH, '//form[@id="SirketTemsilciForm"]/div[1]/input[@name="VergiNo"]').send_keys(myresult[0][11])
        driver.find_element(By.XPATH, '//form[@id="SirketTemsilciForm"]/div[2]/input[@name="TemsilciTcNo"]').send_keys(username)
        driver.find_element(By.XPATH, '//form[@id="SirketTemsilciForm"]/div[3]/input[@name="Password"]').send_keys(password)
        driver.find_element(By.XPATH, '//form[@id="SirketTemsilciForm"]/div[4]/input[@type="submit"]').click()

    if myresult[0][13] == "4":
        driver.find_element(By.XPATH, '//ul[@id="Div_IlkGirisTab"]/li[4]').click()
        time.sleep(1)
        driver.find_element(By.XPATH, '//form[@id="GercekKisiYatirimci_MusteriForm"]/div[1]/input[@name="UserName"]').send_keys(username)
        driver.find_element(By.XPATH, '//form[@id="GercekKisiYatirimci_MusteriForm"]/div[2]/input[@name="Password"]').send_keys(password)
        driver.find_element(By.XPATH, '//form[@id="GercekKisiYatirimci_MusteriForm"]/div[3]/input[@type="submit"]').click()

    time.sleep(1)

    sureUyari = driver.find_elements(By.XPATH, "//span[contains(text(),'Bir Sonraki Giriş SMS')]")
    if(len(sureUyari) > 0):
        mycursor.execute("UPDATE turib_auth SET status='7' WHERE id="+str(sys.argv[1]))
        mydb.commit()
        time.sleep(3)
        mycursor.execute("DELETE FROM turib_auth WHERE id="+str(sys.argv[1]))
        mydb.commit()
        driver.quit()
        sys.exit()


    sifreUyari = driver.find_elements(By.XPATH, "//span[contains(text(),'Kullanıcı adı veya şifre hatalı.!!')]")
    if len(sifreUyari) > 0:
        mycursor.execute("UPDATE turib_auth SET status='6' WHERE id="+str(sys.argv[1]))
        mydb.commit()
        print("Kullanıcı adı veya şifre hatalı.!!!")
        driver.quit()
        sys.exit()


    while True:
        mydb.commit()
        mycursor.execute("SELECT * FROM turib_auth Where id="+str(sys.argv[1]))
        myresult = mycursor.fetchall()
        print(myresult)
        
        try:
            if myresult[0][5] != '' or myresult[0][5] != None:
                kalansure = driver.find_element(By.ID, 'LblKalanSure').text
                print(kalansure)
                if kalansure == '0' or kalansure == 0:
                    mycursor.execute("UPDATE turib_auth SET status='5' WHERE id="+str(sys.argv[1]))
                    mydb.commit()
                    mycursor.execute("UPDATE turib_auth SET smscode='' WHERE id="+str(sys.argv[1]))
                    mydb.commit()
                    driver.quit()
                    sys.exit()
                    break
                else:
                    driver.find_element(By.ID, 'AccessCode').send_keys(myresult[0][5])
                    driver.find_element(By.ID, 'AccessCode').send_keys(Keys.ENTER)
                    time.sleep(1)

                    page_result = driver.execute_script('return document.readyState;')

                    smsKodUyari = driver.find_elements(By.XPATH, "//span[contains(text(),'Giriş yaptığınız mesaj kodu sistemdeki ile uyuşmuyor')]")
                    #smsKodUyari = driver.execute_script("return document.evaluate(\"\", document, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null).snapshotLength")
                    current_url = driver.current_url
                    print(str(len(smsKodUyari)))
                    print(page_result)
                    print(current_url)
                    #get current url
                    
                    #metisMenu

                    if len(smsKodUyari) > 0 and page_result == 'complete' and current_url == 'https://platform.turib.com.tr/Account/Login':
                        driver.find_element(By.ID, 'AccessCode').clear()
                        print("smsKodUyari")
                        mycursor.execute("UPDATE turib_auth SET status='4', smscode='' WHERE id="+str(sys.argv[1]))
                        mydb.commit()


                    if len(smsKodUyari) == 0 and page_result == 'complete' and current_url == 'https://platform.turib.com.tr/Home/Index':
                        mycursor.execute("UPDATE turib_auth SET status='2' WHERE id="+str(sys.argv[1]))
                        mydb.commit()
                        break
                    
        except Exception as e:
            print("ERROR : "+str(e))
            pass
        
        time.sleep(1)
    #https://platform.turib.com.tr/Raporlar/PiyasaEkrani

    #get cookie __RequestVerificationToken, ASP.NET_SessionId, .ASPXAUTH
    cookies = driver.get_cookies()
    #print(cookies)
    mycursor.execute("UPDATE turib_auth SET cookies='"+json.dumps(cookies)+"' WHERE id="+str(sys.argv[1]))
    mydb.commit()

    driver.get("https://platform.turib.com.tr/KisiBilgileri")
    time.sleep(2)


    while True:
        
        try:
            ad_soyad = driver.execute_script("return $('.user-box').children()[0].children[0].children[1].children[0].textContent")
            #ad_soyad = driver.find_element(By.XPATH("//li[@class='dropdown user-box']/a/div/div[2]/span[1]")).text
            sicil_no = driver.execute_script("return $('.user-box').children()[0].children[0].children[1].children[1].textContent")
            #sicil_no = driver.find_element(By.XPATH("//li[@class='dropdown user-box']/a/div/div[2]/span[2]")).text
            print(ad_soyad)
            print(sicil_no)
            if ad_soyad!='':
                sql = "UPDATE turib_auth SET ad_soyad ='"+str(ad_soyad)+"', sicil_no ='"+str(sicil_no)+"' WHERE id="+str(sys.argv[1])
                
                mycursor.execute(sql)
                mydb.commit()
                break
        except:
            pass
        time.sleep(1)

    script =    '''
            var uzunluk = document.querySelectorAll('[data-caption="Aracı Kurum Kodu"]').length
            var banka = "";
            var hesapno = "";
            var erey = [];
            for(var i=0; i < uzunluk; i++){
                banka = document.querySelectorAll('[data-caption="Aracı Kurum Kodu"]')[i].textContent;
                hesapno = document.querySelectorAll('[data-caption="Hesap No"]')[i].textContent;
                var bankahesap = banka+'-'+hesapno;
                erey.push(bankahesap);
            }
            return JSON.stringify(erey);
            '''
            
    banka_bilgileri = driver.execute_script(script)  
    mycursor.execute("UPDATE turib_auth SET banka_hesaplar='"+banka_bilgileri+"' WHERE id="+str(sys.argv[1]))
    mydb.commit()           

        
    '''i=0
    while True:
        
        banka_hesaplar = driver.execute_script("var arr = []; for(var i=0; i<$('.dx-item-content.dx-list-item-content').length; i++){ arr.push($('.dx-item-content.dx-list-item-content')[i].textContent) } return JSON.stringify(arr);")
        print(banka_hesaplar)
        
        if(len(json.dumps(banka_hesaplar)) > 5):
            mycursor.execute("UPDATE turib_auth SET banka_hesaplar='"+json.dumps(banka_hesaplar)+"' WHERE id="+str(sys.argv[1]))
            mydb.commit()
            driver.quit()
            os._exit()
            sys.exit()

            break

        if i >= 3:
            break
        else:
            i = i + 1
        
        time.sleep(1)'''

    driver.quit()
    # terminate the execution of the script
    sys.exit(1)
    


else:
    print(myresult[0][6])
    driver.get('https://platform.turib.com.tr/Account/Login')
    load_cookie(driver, myresult[0][6])


driver.get("https://platform.turib.com.tr/EmirAlim")
time.sleep(2)


'''while True:
    
    try:
        ad_soyad = driver.execute_script("return $('.user-box').children()[0].children[0].children[1].children[0].textContent")
        #ad_soyad = driver.find_element(By.XPATH("//li[@class='dropdown user-box']/a/div/div[2]/span[1]")).text
        sicil_no = driver.execute_script("return $('.user-box').children()[0].children[0].children[1].children[1].textContent")
        #sicil_no = driver.find_element(By.XPATH("//li[@class='dropdown user-box']/a/div/div[2]/span[2]")).text
        print(ad_soyad)
        print(sicil_no)
        if ad_soyad!='':
            sql = "UPDATE turib_auth SET ad_soyad ='"+str(ad_soyad)+"', sicil_no ='"+str(sicil_no)+"' WHERE id="+str(sys.argv[1])
            
            mycursor.execute(sql)
            mydb.commit()
            break
    except:
        pass
    time.sleep(1)
    
i=0
while True:
    
    banka_hesaplar = driver.execute_script("var arr = []; for(var i=0; i<$('.dx-item-content.dx-list-item-content').length; i++){ arr.push($('.dx-item-content.dx-list-item-content')[i].textContent) } return JSON.stringify(arr);")
    print(banka_hesaplar)
    
    if(len(json.dumps(banka_hesaplar)) > 5):
        mycursor.execute("UPDATE turib_auth SET banka_hesaplar='"+json.dumps(banka_hesaplar)+"' WHERE id="+str(sys.argv[1]))
        mydb.commit()
        break

    if i >= 3:
        break
    else:
        i = i + 1
    
    time.sleep(1)


mycursor.execute("SELECT * FROM kriterler Where user_id="+str(user_id)+" and status='0' ORDER BY id DESC LIMIT 1")
myresult = mycursor.fetchall()

if(len(myresult) == 0):
    sys.exit()'''


while True:
    mydb.commit()

    print(sys.argv)
    
    mycursor.execute("SELECT * FROM kriterler Where id="+str(sys.argv[3]))
    myresult = mycursor.fetchall()


    
    if(len(myresult) > 0):
    
        if(myresult[0][9] == '10'):
            print('Bekle')
            time.sleep(1)
        
        else:

            

            mycursor.execute("SELECT COUNT(*) AS total FROM turib_auth WHERE user_id="+str(user_id))
            myresultx = mycursor.fetchone()
            if myresultx[0]==0:
                sys.exit()
            
            if len(sys.argv) == 3:
                mycursor.execute("UPDATE kriterler SET status='1' WHERE id="+str(myresult[0][0]))
                mydb.commit()

            if len(sys.argv) == 4:
                mycursor.execute("UPDATE kriterler SET status='1' WHERE id="+str(myresult[0][0]))
                mydb.commit()

            elus = myresult[0][2]
            fiyat = myresult[0][3]
            cookiesx = json.loads(cookies)


            print(type(cookiesx))

            # print the keys and values
            for key in cookiesx:
                
                if(key['name'] == '__RequestVerificationToken'):
                    RequestVerificationToken = key['value']
                if(key['name'] == 'ASP.NET_SessionId'):
                    ASPNET_SessionId = key['value']
                if(key['name'] == '.ASPXAUTH'):
                    ASPXAUTH = key['value']

            
            
            donendeger = alimEmirleriListesi(RequestVerificationToken, ASPNET_SessionId, ASPXAUTH)

            donendeger = json.loads(donendeger)['data']
            
            
            arrFiyat = []
            arrMiktar = []
            arrKalan = []
            for key in donendeger:
                #replace key['Fiyat'] to float
                
                print(key['Fiyat'])
                print(fiyat)
                if key['ISIN_ELUS_KODU'] == elus and key['Fiyat'] <= float(fiyat.replace(',','.')) and float(key['Kalan']) > 0:
                    arrFiyat.append(key['Fiyat'])
                    arrMiktar.append(key['Miktar'])
                    arrKalan.append(key['Kalan'])
                    print(key['Fiyat'])
                    
            if len(arrFiyat) > 0:
                minIndex = arrFiyat.index(min(arrFiyat))
                inputFiyat = arrFiyat[minIndex]
                inputMiktar = arrMiktar[minIndex]
                inputKalan = arrKalan[minIndex]
                break
    time.sleep(1)

print("Fiyat")
print(inputFiyat)
print("Miktar")
print(inputMiktar)
print("Kalan")
print(inputKalan)

mycursor.execute("UPDATE kriterler SET onay='1', kalanmiktar='"+str(inputKalan)+"' ,bulunanfiyat='"+str(inputFiyat)+"', bulunanmiktar='"+str(inputMiktar)+"' WHERE id="+str(sys.argv[3]))
mydb.commit()

banka = ''

while True:
    mydb.commit()
    
    mycursor.execute("SELECT * FROM kriterler WHERE id="+str(sys.argv[3]))
    myresult = mycursor.fetchall()
    
    try:
        print(myresult[0][11])
    except:
        driver.quit()
        os._exit(0)

    if myresult[0][11] == 3:
        mycursor.execute("DELETE FROM kriterler WHERE id="+str(sys.argv[3]))
        mydb.commit()
        driver.quit()
        sys.exit()


    if myresult[0][11] == '2' or myresult[0][11] == 2:
        
        banka = myresult[0][9]
        break

    time.sleep(1)    


driver.execute_script("$('#EmirAlim_YatirimHesap').children()[0].click()")
time.sleep(0.1)

script =    '''
            for(var i=0; i< $('.dx-item-content.dx-list-item-content').length; i++){
                if($('.dx-item-content.dx-list-item-content')[i].textContent=='BANKA'){
                    $('.dx-item-content.dx-list-item-content')[i].click()
                    break
                }
            }
            '''

replaced_script = script.replace('BANKA', banka)

driver.execute_script(replaced_script)
#driver.execute_script("$('.dx-item-content.dx-list-item-content')[0].click()")
time.sleep(0.1)
driver.execute_script("$('#myModal').modal('show');")
time.sleep(15)


driver.find_element(By.XPATH, '(//input[@aria-label="Tabloda arama yap"])[1]').send_keys(elus)

#script = "var cell = document.evaluate(`//td[text()='"+elus+"']`, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;"
#print(script)
#driver.execute_script(script)
time.sleep(1.5)
#driver.execute_script("cell.parentElement.children[0].children[0].click()")
driver.execute_script("document.querySelectorAll('[title=\"Ekle\"]')[0].click()")
time.sleep(0.1)
#driver.find_element(By.XPATH, '//input[@id="Fiyat"]').send_keys(str(inputFiyat).replace('.',','))
driver.execute_script("$('#Fiyat').val('"+str(inputFiyat).replace('.',',')+"')")
time.sleep(0.1)
#driver.find_element(By.XPATH, '//label[@for="Fiyat"]').click()
time.sleep(0.1)

'''yukMiktar = 0
if len(str(inputMiktar)) > 3:
    yukMiktar = inputMiktar / 1000
else:
    yukMiktar = inputMiktar'''

el = driver.find_element(By.ID, "Fiyat")
slow_type(el, str(inputFiyat))


inputMiktar = str(inputMiktar).split('.')[0]

el = driver.find_element(By.ID, "Miktar")
slow_type(el, str(inputMiktar))

driver.find_element(By.XPATH, '//label[@for="Fiyat"]').click()

#driver.find_element(By.XPATH, '//input[@id="Miktar"]').send_keys(yukMiktar)
#driver.execute_script("$('#Miktar').val("+str(inputMiktar)+")")
time.sleep(0.1)

driver.execute_script("$('#BtnKaydet').click()")

driver.quit()
sys.exit()












            

        
        
   
        

