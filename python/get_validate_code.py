import ddddocr
import sys

ocr = ddddocr.DdddOcr()
with open("storage/" + sys.argv[1] + "/orc/1.jpg", 'rb') as f:
    img_bytes = f.read()
res = ocr.classification(img_bytes)
print(res)
