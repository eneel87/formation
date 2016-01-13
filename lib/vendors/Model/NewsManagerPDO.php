<?php
namespace Model;

use \Entity\News;
use \Entity\Member;

class NewsManagerPDO extends NewsManager
{
  public function getList($debut = -1, $limite = -1)
  {
    $sql = 'SELECT FNC_id as id, FNC_fk_FMC as auteurId, FNC_title as titre, FNC_content as contenu, FNC_dateadd as dateAjout, FNC_dateupdate as dateModif, FMC_login as login
            FROM t_for_newsc
            LEFT OUTER JOIN t_for_memberc ON FNC_fk_FMC = FMC_id
            ORDER BY FNC_id DESC';
    
    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }
    
    $requete = $this->dao->query($sql);

    $listeNews = array();

    while ($data = $requete->fetch())
    {
      $News = new News();
      $News->setId($data['id']);
      $News->setAuteurId($data['auteurId']);
      $News->setTitre($data['titre']);
      $News->setContenu($data['contenu']);
      $News->setDateAjout(new \DateTime($data['dateAjout']));
      $News->setDateModif(new \DateTime($data['dateModif']));
      $News->Membre = new Member(array('login'=>$data['login']));
      $listeNews[] = $News;
    }

    $requete->closeCursor();
    
    return $listeNews;
  }

  public function getListUsingMemberId($id, $debut = -1, $limite = -1)
  {
    $sql = $sql = 'SELECT FNC_id as id, FNC_title as titre, FNC_content as contenu, FNC_dateadd as dateAjout, FNC_dateupdate as dateModif, FMC_login as login
            FROM t_for_newsc
            INNER JOIN t_for_memberc ON FNC_fk_FMC = FMC_id
            WHERE FNC_fk_FMC = :id
            ORDER BY FNC_id DESC';

    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }

    $requete = $this->dao->prepare($sql);
    $requete->bindValue(':id', $id, \PDO::PARAM_INT);
    $requete->execute();

    $listeNews = array();

    while ($data = $requete->fetch())
    {
      $News = new News();
      $News->setId($data['id']);
      $News->setTitre($data['titre']);
      $News->setContenu($data['contenu']);
      $News->setDateAjout(new \DateTime($data['dateAjout']));
      $News->setDateModif(new \DateTime($data['dateModif']));
      $News->Membre = new Member(array('login' => $data['login']));
      $listeNews[] = $News;
    }

    $requete->closeCursor();

    return $listeNews;
  }

  public function getUnique($id)
  {
    $requete = $this->dao->prepare('SELECT FNC_id as id, FNC_fk_FMC as auteurId, FMC_login as login, FNC_title as titre, FNC_content as contenu, FNC_dateadd as dateAjout, FNC_dateupdate as dateModif
                                    FROM t_for_newsc
                                    LEFT OUTER JOIN t_for_memberc ON FNC_fk_FMC = FMC_id
                                    WHERE FNC_id = :id');

    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $requete->execute();
    
    $data = $requete->fetch();

    if($data)
    {
      $News = new News();
      $News->setId($data['id']);
      $News->setAuteurId($data['auteurId']);
      $News->setTitre($data['titre']);
      $News->setContenu($data['contenu']);
      $News->setDateAjout(new \DateTime($data['dateAjout']));
      $News->setDateModif(new \DateTime($data['dateModif']));
      $News->Membre = new Member(array('login' => $data['login']));

      return $News;

      /*
       $line=array();
      if($line=$stmt->fetch())
          return new News($line)->__set('Member', new Member($line))

      $News->Member=85

      */
    }

    return null;
    

  }

  public function count()
  {
    return $this->dao->query('SELECT COUNT(*) FROM t_for_newsc')->fetchColumn();
  }

  public function countUsingMemberId($id)
  {
    $sql = 'SELECT COUNT(*) FROM t_for_newsc WHERE FNC_fk_FMC = :id';

    $requete = $this->dao->prepare($sql);

    $requete->bindValue('id', $id, \PDO::PARAM_INT);

    $requete->execute();

    if($result = $requete->fetchColumn())
    {
      return $result;
    }

    return 0;
  }

  protected function add(News $news)
  {
    $sql = 'INSERT INTO t_for_newsc SET FNC_fk_FMC = :memberId, FNC_title = :titre, FNC_content = :contenu, FNC_dateadd = NOW(), FNC_dateupdate = NOW()';

    $requete = $this->dao->prepare($sql);

    $requete->bindValue(':memberId', $news->auteurId(), \PDO::PARAM_INT);
    $requete->bindValue(':titre', $news->titre(), \PDO::PARAM_STR);
    $requete->bindValue(':contenu', $news->contenu(), \PDO::PARAM_STR);
    
    $requete->execute();
  }

  protected function modify(News $news)
  {
    $requete = $this->dao->prepare('UPDATE t_for_newsc SET FNC_title = :titre, FNC_content = :contenu, FNC_dateupdate = NOW() WHERE FNC_id = :id');
    
    $requete->bindValue(':titre', $news->titre(), \PDO::PARAM_STR);
    $requete->bindValue(':contenu', $news->contenu(), \PDO::PARAM_STR);
    $requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);
    
    $requete->execute();
  }

  public function delete($id)
  {
    $sql = 'DELETE FROM t_for_newsc WHERE FNC_id =:id';

    $requete = $this->dao->prepare($sql);
    $requete->bindValue('id', $id, \PDO::PARAM_INT);
    $requete->execute();
  }
}