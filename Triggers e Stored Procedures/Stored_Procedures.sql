#Esta página contem os Stored Procedures que serão chamados pela aplicação PhP.
#A cada uma destas operações está associado ainda um trigger, que insere um 
#novo elemento na tabela sequencia para ser usado.

#CRIA_CAMPO
CREATE DEFINER=`ist165865`@`%` PROCEDURE `cria_campo`(givenid int, nomecampo varchar(255), giventype int)
BEGIN
	#Creating a new Field
	set @maximo = (SELECT MAX(contador_sequencia) FROM sequencia);
	set @countRegisto = (SELECT MAX(campocnt) FROM campo)+1;
	INSERT INTO campo (userid, typecnt, campocnt, idseq, ativo, nome, pcampocnt) 
	VALUES (givenid, giventype, @countRegisto, @maximo, TRUE, nomecampo, NULL);
END

#CRIA_PAGINA
CREATE DEFINER=`ist165865`@`%` PROCEDURE `cria_pagina`(userid int, nomepagina varchar(255))
BEGIN
	#Creating a new Page
	 set @maximo = (SELECT MAX(contador_sequencia) FROM sequencia);
     set @pagina = (SELECT MAX(pagecounter) from pagina)+1;
     INSERT INTO pagina (userid, nome, ativa, ppagecounter, idseq, pagecounter) 
	 VALUES (userid, nomepagina, TRUE, NULL, @maximo, @pagina);
END

#CRIA_REGISTO
CREATE DEFINER=`ist165865`@`%` PROCEDURE `cria_registo`(givenid int, nomeregisto varchar(255), giventype int)
BEGIN
	#Creating a new Registry
	set @maximo = (SELECT MAX(contador_sequencia) FROM sequencia);
	set @countRegisto = (SELECT MAX(regcounter) FROM registo)+1;
	INSERT INTO registo (userid, typecounter, regcounter, nome, ativo, idseq, pregcounter) 
	VALUES (givenid, giventype, @countRegisto, nomeregisto, TRUE, @maximo, NULL);
END

#CRITA_TIPO_REGISTO
CREATE DEFINER=`ist165865`@`%` PROCEDURE `cria_tipo_registo`(userid int, nometipo varchar(255))
BEGIN
	#Creating a new type
	set @maximo = (SELECT MAX(contador_sequencia) FROM sequencia);
    set @tipoRegisto = (SELECT MAX(typecnt) from tipo_registo)+1;
    INSERT INTO tipo_registo (userid, nome, ativo, ptypecnt, idseq, typecnt) 
	VALUES (userid, nometipo, TRUE, NULL, @maximo, @tipoRegisto);
END

#CRIA_VALOR
CREATE DEFINER=`ist165865`@`%` PROCEDURE `cria_valor`(givenid int, nomeregisto varchar(255), giventype int, fieldname varchar(255), val varchar(255))
BEGIN
    #Converting nomeregisto -> regid
    set @regid = (SELECT MAX(regcounter) FROM registo 
    WHERE userid = givenid AND typecounter = giventype AND nome = nomeregisto AND ativo = 1);
    
    #Converting fieldname -> campoid
    set @campoid = (SELECT MAX(campocnt) FROM campo
    WHERE userid = givenid AND typecnt = giventype AND nome = fieldname AND ativo = 1);
     
	#Creating a new Value
	set @maximo = (SELECT MAX(contador_sequencia) FROM sequencia);
	INSERT INTO valor (userid, typeid, regid, campoid, valor, idseq, ativo, pcampoid) 
	VALUES (givenid, giventype, @regid, @campoid, val, @maximo, TRUE, NULL);
END

#ELIMINA_CAMPO
CREATE DEFINER=`ist165865`@`%` PROCEDURE `elimina_campo`(givenid int, giventype int, givenCampo varchar(255))
BEGIN
     UPDATE campo set ativo = 0 
	 WHERE userid = givenid AND typecnt = giventype AND nome = givenCampo AND ativo = 1;
END

#ELIMINA_PAGINA
CREATE DEFINER=`ist165865`@`%` PROCEDURE `elimina_pagina`(givenid int, givenpagina varchar(255))
BEGIN
     UPDATE pagina set ativa = 0 
	 WHERE userid = givenid AND nome = givenpagina AND ativa = 1;
END

#ELIMINA_TIPO_REGISTO
CREATE DEFINER=`ist165865`@`%` PROCEDURE `elimina_tipo_registo`(givenid int, giventipo varchar(255))
BEGIN
     UPDATE tipo_registo set ativo = 0 
	 WHERE userid = givenid AND nome = giventipo AND ativo = 1;
END