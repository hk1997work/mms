import fitz
import sys
import os

id = sys.argv[1]
path = f"storage/certificate/{id}.pdf"
with fitz.open(path) as doc:
    output_dir = f"storage/jpg/{id}"
    os.makedirs(output_dir, exist_ok=True)

    for pg in range(doc.page_count):
        page = doc[pg]
        mat = fitz.Matrix(3, 3)
        pix = page.get_pixmap(matrix=mat)
        pix.set_dpi(300, 300)
        pix.save("storage/jpg/" + id + "/" + str(pg) + ".jpg")
