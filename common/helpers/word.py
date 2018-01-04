import sys 
import os
from win32com import client as wc
import pythoncom
if (os.path.exists(sys.argv[1])):
	word = wc.Dispatch('Word.Application')
	wc.Visible = 0
	wc.DisplayAlerts = 0
	doc = word.Documents.Open(sys.argv[1])
	html_list_first = sys.argv[1].split('.' , 1)
	html_list_second = html_list_first[0].split('.')
	html_name = html_list_second[-1] + '.html'
	if (not os.path.exists(html_name)):
		doc.SaveAs(html_name, 10)
		doc.Close()
		word.Quit()
	print(html_name)
else:
	print("No file exists!")