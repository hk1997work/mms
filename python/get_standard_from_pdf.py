import sys
import fitz  # PyMuPDF
import pytesseract
import mysql.connector
import requests
import json
from io import BytesIO
from PIL import Image

pytesseract.pytesseract.tesseract_cmd = 'C:/Program Files/Tesseract-OCR/tesseract.exe'


def extract_text_from_pdf(pdf_path):
    if 'http' in pdf_path:
        response = requests.get(pdf_path)
        if response.status_code == 200:
            pdf_data = BytesIO(response.content)
            pdf_document = fitz.open(stream=pdf_data, filetype="pdf")
    else:
        pdf_document = fitz.open(pdf_path)
    full_text = ''
    one_text = ''
    for page_num in range(pdf_document.page_count):
        page = pdf_document[page_num]
        page_text = page.get_text()
        if page_text == '':
            image = page.get_pixmap(matrix=fitz.Matrix(300 / 72, 300 / 72))
            image_data = Image.frombytes("RGB", (image.width, image.height), image.samples)
            page_text = pytesseract.image_to_string(image_data, lang="chi_sim")
        full_text += page_text
        if page_num == 0:
            one_text = page_text.replace(' ', '').replace('\n', '').lower()
    pdf_document.close()
    return one_text, find_matching_lines(full_text)


def find_matching_keywords(text, keywords):
    matching_keywords = []
    for k, v in keywords.items():
        if k in text and v not in matching_keywords:
            matching_keywords.append(v)
    return ', '.join(matching_keywords)


def find_matching_lines(text):
    replacements = [
        ('\n', ''),
        (' ', ''),
        (':', '-'),
        ('(', '（'),
        (')', '）'),
        ('技术说明书', '厂方-技术文件'),
        ('厂方技术文件', '厂方-技术文件'),
        ('JiG', 'JJG'),
        ('JIG', 'JJG'),
        ('刀G', 'JJG'),
        ('JJG一', 'JJG'),
        ('GBIT', 'GB/T'),
        ('GB1T', 'GB/T'),
        ('GB/I', 'GB/T'),
        ('GB/F', 'GB/T'),
        ('GRIT', 'GB/T'),
        ('一', '-'),
        ('－', '-'),
        ('O', '0'),
        ('I', '1'),
    ]
    for old, new in replacements:
        text = text.replace(old, new)
    return text.upper()


def get_data_from_database():
    db_connection = mysql.connector.connect(
        host="127.0.0.1",
        user="enjoyzz",
        password="199362",
        database="erp"
    )
    cursor = db_connection.cursor()
    query = "SELECT id,CONCAT(name1,'-',REPLACE(REPLACE(SUBSTRING_INDEX(name2, '《', 1), 'I', '1'), 'O', '0')) as standard FROM standards_views WHERE `level`=2"
    cursor.execute(query)

    result = cursor.fetchall()

    cursor.close()
    db_connection.close()

    return result


def is_valid_json(text):
    try:
        json_object = json.loads(text)
        return True, json_object
    except json.JSONDecodeError as e:
        return False, text


def main():
    text = sys.argv[1]
    arr_category = {"检定证": "检定证书", "检定结": "检定证书", "校准证": "校准证书", "检测报": "检测报告", "测试报": "检测报告"}
    database_data = get_data_from_database()
    is_json, arr = is_valid_json(text)
    json_array = []
    if not is_json:
        arr = {'0': text}
    for key, value in arr.items():
        one_text, pdf_text = extract_text_from_pdf(value)
        pdf_standard = []
        for record in database_data:
            if record[1] in pdf_text:
                pdf_standard.append(str(record[0]))
        json_array.append({'key': key.replace('key', ''), 'category': find_matching_keywords(one_text, arr_category), 'standards': ','.join(pdf_standard)})
    print(json.dumps(json_array))


if __name__ == "__main__":
    main()
