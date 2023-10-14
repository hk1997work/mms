import fitz
import PyPDF2
import sys
import pyzbar.pyzbar as pyzbar
from PIL import Image

p = sys.argv[1]
pdf = "storage/" + p + "/orc/1.pdf"
doc = fitz.open(pdf)
reader = PyPDF2.PdfReader(pdf)

page = doc.load_page(0)
mat = fitz.Matrix(5, 5)
pix = page.get_pixmap(matrix=mat)
pix.set_dpi(300, 300)
pix.save("storage/" + p + "/orc/1.jpg")

image = Image.open("storage/" + p + "/orc/1.jpg")
decoded_objects = pyzbar.decode(image)
for obj in decoded_objects:
    data = obj.data.decode("utf-8")
    print(data)
