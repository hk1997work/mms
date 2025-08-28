import cv2
import numpy as np
import base64
import json
from itertools import product
import sys
import requests

url = "https://serv.jsmi.com.cn/code"
response = requests.get(url)
jsonJS = response.json()

# 解码大图片和小图片
big_img = base64.b64decode(jsonJS['data']['repData']['originalImageBase64'])
small_img = base64.b64decode(jsonJS['data']['repData']['jigsawImageBase64'])

big_img = cv2.imdecode(np.frombuffer(big_img, np.uint8), -1)
small_img = cv2.imdecode(np.frombuffer(small_img, np.uint8), -1)


# 定义嵌入图片的函数
def embed_image(big_img, small_img, x, y):
    h, w, _ = small_img.shape
    embedded_img = big_img.copy()
    roi = embedded_img[y:y + h, x:x + w]

    mask = (small_img[:, :, 3] / 255.0)  # Alpha channel as mask
    for c in range(0, 3):
        embedded_img[y:y + h, x:x + w, c] = (1 - mask) * embedded_img[y:y + h, x:x + w, c] + mask * small_img[:, :, c]

    return embedded_img


# 初始化最佳相似度和最佳嵌入图像
best_similarity = 0.0
best_x_coordinate = None

# 遍历所有可能的位置，计算相似度
for x, y in product(range(big_img.shape[1] - small_img.shape[1] + 1), range(big_img.shape[0] - small_img.shape[0] + 1)):
    embedded_img = embed_image(big_img, small_img, x, y)

    # 计算相似度，这里使用模板匹配方法
    similarity = cv2.matchTemplate(big_img, embedded_img, cv2.TM_CCOEFF_NORMED)

    if similarity > best_similarity:
        best_similarity = similarity
        best_x_coordinate = x
print(jsonJS['data']['repData']['secretKey'])
print(jsonJS['data']['repData']['token'])
print('{"x":' + str(best_x_coordinate) + ',"y":5}')
