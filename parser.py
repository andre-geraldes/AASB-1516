'''
Created on Jan 9, 2016

@author: hdg
'''

import openpyxl
import json

workbook = openpyxl.load_workbook('info.xlsx')
worksheet = workbook['Sheet1']

data1, data2, data3 = [], [], []
value = ''

print (worksheet.cell (row = 2, column = 2).value)

for current in range (1, 66):
    data1.append ({'name' : worksheet.cell (row = 0, column = current).value})

for current in range (1, 464):
    data2.append ({'name' : worksheet.cell (row = current, column = 0).value})

for row in range (1, 464):
    for column in range (1, 66):
        current = worksheet.cell (row = row, column = column).value
        if current != '  ':
            if 'MUT' in current:
                value += '1'
            if 'DOWN' in current:
                value += '2'
            if 'AMP' in current:
                value += '3'
            if 'UP' in current:
                value += '4'
            if 'HOMDEL' in current:
                value += '5'
            data3.append ({"source" : row - 1, "target" : column - 1, "value" : int (value)})
            value = ''

data_string = json.dumps({'gene' : data1, 'caseID' : data2, 'links' : data3}, indent = 4)

f = open('newfile.json','w')
f.write(data_string)
f.close()


# Cells mal formatadas
# 2 Cells com MUT, como funciona?
# Diferentes tipos de MUT (Missense, Inframe, Truncating)
# Pdf com resultados diferentes do Excel