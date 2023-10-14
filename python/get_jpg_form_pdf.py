import fitz
import PyPDF2
import sys
import pyzbar.pyzbar as pyzbar
from PIL import Image

p = sys.argv[1]
pdf = "storage/certificate/" + p + ".pdf"
doc = fitz.open(pdf)
reader = PyPDF2.PdfReader(pdf)
pageNum = len(reader.pages)
for pg in range(0, pageNum):
    page = doc.load_page(pg)
    mat = fitz.Matrix(3, 3)
    pix = page.get_pixmap(matrix=mat)
    pix.set_dpi(300, 300)
    pix.save("storage/jpg/" + p + "/" + str(pg) + ".jpg")
