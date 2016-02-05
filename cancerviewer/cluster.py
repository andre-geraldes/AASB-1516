import re

class MatrixNum:

    def __init__(self, rows, cols):
        self.mat = []
        for i in range(rows):
            self.mat.append([])
            for j in range(cols):
                self.mat[i].append(0.0)

    def __getitem__(self, n):
        return self.mat[n]

    def numRows (self):
        return len(self.mat)

    def numCols (self):
        return len(self.mat[0])

    def getValue (self, i, j):
        return self.mat[i][j]

    def setValue(self, i, j, value):
        self.mat[i][j] = value

    def printmat(self):
        for r in self.mat: print(r)
        print()

    def minDistIndexes (self):
        m = self.mat[1][0]
        res= (1,0)
        for i in range(self.numCols()):
            for j in range(i+1, self.numRows()):
                if self.mat[j][i] < m:
                    m = self.mat[j][i]
                    res = (j, i)
        return res

    def addRow(self, newrow):
        self.mat.append(newrow)

    def addCol(self, newcol):
        for r in range(self.numRows()):
            self.mat[r].append(newcol[r])

    def removeRow(self, ind):
        del self.mat[ind]

    def removeCol(self, ind):
        for r in range(self.numRows()):
            del self.mat[r][ind]

    def copy(self):
        newm = MatrixNum(self.numRows(), self.numCols())
        for i in range(self.numRows()):
            for j in range(self.numCols()):
                newm.mat[i][j] = self.mat[i][j]
        return newm


class BinaryTree:
    def __init__(self, val, dist=0, left = None, right = None):
        self.value = val
        self.distance = dist
        self.left = left
        self.right = right


    def getCluster(self):
        res = []
        if self.value >= 0:
            res.append(self.value)
        else:
            if (self.left != None):
                res.extend(self.left.getCluster())
            if (self.right != None):
                res.extend(self.right.getCluster())
        return res

class Config:

    def __init__ (self, filename):
        self.dic = {}
        self.parseFile(filename)

    def parseFile (self, filename):
        file = open (filename, 'r')
        split = file.readline().split (',')
        self.space, self.default = int (split[0].replace(' ','')), int (split[1].replace(' ',''))
        self.mut = self.default/2

        for line in file:
            line = line.replace(' ','')
            tokens = line.split (',')
            for i in range (1, len (tokens) - 1):
                for j in range (i + 1, len (tokens)):
                    self.dic[tokens[i] + tokens[j]] = int (tokens[0])
                    self.dic[tokens[j] + tokens[i]] = int (tokens[0])

        file.close ()

class ClustHier:

    def __init__(self, filename, configfile):
        self.config = Config (configfile)
        self.parseFile (filename)

    def parseFile (self, filename):
        file = open (filename, 'r')
        matrix = []

        for line in file:
            token = line.split ('-')
            token.remove ('\n')
            matrix.append (token)

        file.close()

        self.maxcol = len (matrix[0])
        self.maxrow = len (matrix)
        self.matdistscols = MatrixNum (self.maxcol, self.maxcol)
        self.matdistsrows = MatrixNum (self.maxrow, self.maxrow)
        self.createDistMatrixes (matrix)

    def createDistMatrixes (self, matrix):
        matrixcols, matrixrows = [], []

        for i in range (self.maxcol):
            matrixcols.append ([])
            for j in range (self.maxrow):
                matrixcols[i].append (matrix[j][i])

        for i in range (self.maxrow):
            matrixrows.append ([])
            for j in range (self.maxcol):
                matrixrows[i].append (matrix[i][j])

        for i in range (self.maxcol):
            j = 0
            while j < i:
                dis = self.distance (matrixcols[i], matrixcols[j])
                self.matdistscols[i][j] = dis
                j += 1

        for i in range (self.maxrow):
            j = 0
            while j < i:
                dis = self.distance (matrixrows[i], matrixrows[j])
                self.matdistsrows[i][j] = dis
                j += 1

    def distanceCols (self, tree1, tree2):
        c1 = tree1.getCluster()
        c2 = tree2.getCluster()
        sd = 0.0;

        for i in range(len(c1)):
            for j in range(len(c2)):
                sd += self.matdistscols.getValue(c1[i], c2[j])

        return sd/(len(c1)*len(c2))

    def distanceRows (self, tree1, tree2):
        c1 = tree1.getCluster()
        c2 = tree2.getCluster()
        sd = 0.0;

        for i in range(len(c1)):
            for j in range(len(c2)):
                sd += self.matdistsrows.getValue(c1[i], c2[j])

        return sd/(len(c1)*len(c2))

    def distance (self, list1, list2):
        sim = 0.0;

        for i in range(len(list1)):
            sim += self.similarity (list1[i], list2[i])

        return 1 - (sim / len (list1))

    def similarity (self, string1, string2):
        pattern_trunc = '[A-Z][0-9]+\*'
        pattern_missence = '[A-Z][0-9]+[A-Z]'
        total = 0

        if string1 == '0' and string2 == '0':
            return self.config.space

        if (string1[len (string1) - 1] == ';'):
            string1 = string1[:-1]

        if (string2[len (string2) - 1] == ';'):
            string2 = string2[:-1]

        string1 = string1.replace('MUT: ', '')
        string2 = string2.replace('MUT: ', '')
        string1 = string1.replace (';', ',')
        string2 = string2.replace (';', ',')
        tokens1 = string1.split (',')
        tokens2 = string2.split (',')

        for i in tokens1:
            if i in tokens2:
                tokens1.remove (i)
                tokens2.remove (i)
                total += self.config.default

        for i in tokens1:
            for j in tokens2:
                current = i + j
                if current in self.config.dic:
                    total += self.config.dic[current]
                elif 'fs' in i and 'fs' in j:
                    total += self.config.mut
                elif 'splice' in i and 'splice' in j:
                    total += self.config.mut
                elif 'delins' in i and 'delins' in j:
                    total += self.config.mut
                elif 'del' in i and 'del' in j:
                    total += self.config.mut
                elif 'ins' in i and 'ins' in j:
                    total += self.config.mut
                elif re.match(pattern_trunc, i) and re.match(pattern_trunc, j):
                    total += self.config.mut
                elif re.match(pattern_missence, i) and re.match(pattern_missence, j):
                    total += self.config.mut

        return total

    def executeClusteringCols (self):
        trees = []
        tableDist = self.matdistscols.copy()

        for i in range(self.matdistscols.numRows()):
            t = BinaryTree(i)
            trees.append(t)

        for k in range(self.matdistscols.numRows(), 1, -1):
            i, j = tableDist.minDistIndexes()
            n = BinaryTree(-1, tableDist.getValue(i, j)/2.0, trees[i], trees[j])

            if k > 2:
                trees.pop(i)
                trees.pop(j)
                tableDist.removeRow(i)
                tableDist.removeRow(j)
                tableDist.removeCol(i)
                tableDist.removeCol(j)
                dists = []

                for x in range(len(trees)):
                    dists.append(self.distanceCols(n, trees[x]))

                tableDist.addRow(dists)
                cdists = []

                for y in range(len(dists)):
                    cdists.append(dists[y])

                cdists.append(0.0)
                tableDist.addCol(cdists)
                trees.append(n)
            else:
                return n

    def executeClusteringRows (self):
        trees = []
        tableDist = self.matdistsrows.copy()

        for i in range(self.matdistsrows.numRows()):
            t = BinaryTree(i)
            trees.append(t)

        for k in range(self.matdistsrows.numRows(), 1, -1):
            i, j = tableDist.minDistIndexes()
            n = BinaryTree(-1, tableDist.getValue(i, j)/2.0, trees[i], trees[j])

            if k > 2:
                trees.pop(i)
                trees.pop(j)
                tableDist.removeRow(i)
                tableDist.removeRow(j)
                tableDist.removeCol(i)
                tableDist.removeCol(j)
                dists = []

                for x in range(len(trees)):
                    dists.append(self.distanceRows (n, trees[x]))

                tableDist.addRow(dists)
                cdists = []

                for y in range(len(dists)):
                    cdists.append(dists[y])

                cdists.append(0.0)
                tableDist.addCol(cdists)
                trees.append(n)
            else:
                return n


if __name__ == '__main__':
    hc = ClustHier('matrix.txt', 'configs.txt')
    arv1 = hc.executeClusteringRows ()
    arv2 = hc.executeClusteringCols ()
    print (arv1.getCluster(), arv2.getCluster())
