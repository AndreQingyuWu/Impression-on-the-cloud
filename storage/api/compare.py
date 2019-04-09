import os
import numpy as np
import math
import jieba 
import os
import re
import codecs

class Compare(object):
    def __init__(self,search_key,search_list,search_mode):
        if search_key==None or search_list==None or search_mode==None:
            print('false')
        self.search_key=search_key
        self.search_list=list(search_list)
        self.search_mode=int(search_mode)
        self.text = None
        self.input_text=None
        self.stop=None
        self.document=None
        self.D=None
        self.avgdl=None
        self.f = [] 
        self.tf = {} 
        self.idf = {} 
        self.k1 = 1.5
        self.b = 0.75
#向量   
    def feature_camparator(self,feature,feature_list,i):
        x1=feature
        #print(type(feature))
        #print(feature[2])
        x2=feature_list[i]
        x1=np.array(x1)
        x2=np.array(x2)
        num = np.dot(np.transpose(x1) ,x2)
        denom = np.linalg.norm(x1) * np.linalg.norm(x2)  
        cos = num / denom 
        sim = 0.5 + 0.5 * cos 
        return sim 
#文本
    def inition(self,docs):
        self.D = len(docs)
        self.avgdl = sum([len(doc)+ 0.0 for doc in docs]) / self.D
        for doc in docs:
            tmp = {}
            for word in doc:
                tmp[word] = tmp.get(word, 0) + 1  
            self.f.append(tmp)
            for k in tmp.keys():
                self.tf[k] = self.tf.get(k, 0) + 1
        for k, v in self.tf.items():
            self.idf[k] = math.log(self.D - v + 0.5) - math.log(v + 0.5)
    def sim(self,doc, index):
        score = 0.0
        for word in doc:
            if word not in self.f[index]:
                continue
            d = len(self.document[index])
            score += (self.idf[word] * self.f[index][word] * (self.k1 + 1) / (self.f[index][word] + self.k1 * (1 - self.b + self.b * d / self.avgdl)))
        return score

    def simall(self,doc):
        scores = []
        for index in range(self.D):
                score = self.sim(doc, index)
                scores.append(score)
        result=list(map(abs,scores))[0]
        return result


    def filter_stop(self,words):
        return list(filter(lambda x: x not in self.stop, words))
    def get_sentences(self,doc):
        line_break = re.compile('[\r\n]')
        delimiter = re.compile('[，。？！；]')
        sentences = []
        for line in line_break.split(doc):
            line = line.strip()
            if not line:
                continue
            for sent in delimiter.split(line):
                sent = sent.strip()
                if not sent:
                    continue
                sentences.append(sent)
        return sentences
    def text_camparator(self,text1,text2):
        self.stop=None
        self.document=None
        self.D=None
        self.avgdl=None
        self.f = [] 
        self.tf = {} 
        self.idf = {} 
        self.k1 = 1.5
        self.b = 0.75
        self.input_text=str(text1)
        self.text=str(text2)
        #print(self.input_text)
        #print(self.text)
        fr = codecs.open('./stopwords.txt', 'r', 'utf-8')
        self.stop=set()
        for word in fr:
            self.stop.add(word.strip())
        fr.close()
        re_zh = re.compile('([\u4E00-\u9FA5]+)')

        input_sents=self.get_sentences(self.input_text)
        input_doc=[]
        for input_sent in input_sents:
            input_words = list(jieba.cut(input_sent))
            input_words = self.filter_stop(input_words)
            input_doc.append(input_words)
        #print(input_doc)
        input_document = list(input_doc[0])

        sents = self.get_sentences(self.text)
        doc = []
        for sent in sents:
            words = list(jieba.cut(sent))
            words = self.filter_stop(words)
            doc.append(words)
        #print(doc)
        self.document = doc

        self.inition(doc)


        result=self.simall(input_document)#为相似度越大越相似
        #print(result)
        return result 
    def camparator(self):
        similarity=[]
        index=[]
        if self.search_mode==1:#矩阵
            for i in range(len(self.search_list)):
                similarity.append(float(self.feature_camparator(self.search_key,self.search_list,i)))
            similarity=np.array(similarity,dtype=float)
            similarity=similarity.argsort()
            index=similarity[::-1]
            print(index)
        elif self.search_mode==2:#文本
            for i in range(len(self.search_list)):
                similarity.append(float(self.text_camparator(self.search_key,self.search_list[i])))
            #print(similarity)
            similarity=np.array(similarity,dtype=float)
            #print(similarity[0])
            similarity=similarity.argsort()
            print(similarity)
            #index=similarity.tolist()
            index=similarity[::-1]
           # math.log(-1)
            print(index)            
        else:
            print('false')
#a=Compare([1,2,3,4],[[4,3,2,1],[1,2,3,4]],1)
#a.camparator()

#b=Compare('海滩',['海滩','太阳'],2)
#b.camparator()