import fitz
import sys
import pyzbar.pyzbar as pyzbar
from PIL import Image

path = sys.argv[1]
with fitz.open(path) as doc:
    page = doc.load_page(0)
    mat = fitz.Matrix(5, 5)
    pix = page.get_pixmap(matrix=mat)
    pix.set_dpi(300, 300)
    img = Image.frombytes("RGB", [pix.width, pix.height], pix.samples)
    decoded_objects = pyzbar.decode(img)
    for obj in decoded_objects:
        data = obj.data.decode("utf-8")
        print(data)
