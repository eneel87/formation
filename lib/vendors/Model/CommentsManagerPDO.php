<?php
namespace Model;

use \Entity\Comment;
use \Entity\Member;
use Entity\News;

class CommentsManagerPDO extends CommentsManager
{
  protected function add(Comment $comment)
  {
    $q = $this->dao->prepare('INSERT INTO t_for_commentc SET FCC_fk_FNC = :newsId, FCC_fk_FMC = :auteurId, FCC_content = :contenu, FCC_dateadd = NOW(), FCC_dateupdate = NOW()');
    
    $q->bindValue(':newsId', $comment->newsId(), \PDO::PARAM_INT);
    $q->bindValue(':auteurId', $comment->auteurId(), \PDO::PARAM_INT);
    $q->bindValue(':contenu', $comment->contenu(), \PDO::PARAM_STR);
    
    $q->execute();
    
    $comment->setId($this->dao->lastInsertId());
  }

  public function getAfterId($news_id, $last_insert_id)
  {
    $sql = 'SELECT *
            FROM T_FOR_commentc
            INNER JOIN T_FOR_memberc ON FCC_fk_FMC = FMC_id
            WHERE FCC_fk_FNC = :news_id AND FCC_id > :last_insert_id
            ORDER BY FCC_id DESC';

    $requete = $this->dao->prepare($sql);

    $requete->bindValue('news_id', $news_id, \PDO::PARAM_INT);
    $requete->bindValue('last_insert_id', $last_insert_id, \PDO::PARAM_INT);
    $requete->execute();

    $Comments_a = array();

    while($data = $requete->fetch())
    {
      $Comment = new Comment();
      $Comment->setAuteurId($data['FCC_fk_FMC']);
      $Comment->setId($data['FCC_id']);
      $Comment->setContenu($data['FCC_content']);
      $Comment->setNewsId($data['FCC_fk_FNC']);
      $Comment->setDateAjout(new \DateTime($data['FCC_dateadd']));
      $Comment->setDateModif(new \DateTime($data['FCC_dateupdate']));
      $Comment->setMembre(new Member(array('login'=>$data['FMC_login'], 'id'=>$data['FMC_id'])));

      $Comments_a[]=$Comment;
    }

    return $Comments_a;
  }

  public function getCommentsUsingMemberId($member_id)
  {
    $sql = 'SELECT *
            FROM T_FOR_commentc
            INNER JOIN T_FOR_newsc ON FCC_fk_FNC = FNC_id
            WHERE FCC_fk_FMC = :member_id
            ORDER BY FCC_id DESC';

    $requete = $this->dao->prepare($sql);

    $requete->bindValue(':member_id', $member_id, \PDO::PARAM_INT);
    $requete->execute();

    $Comments_a = array();

    while($data = $requete->fetch())
    {
      $Comment = new Comment();
      $Comment->setAuteurId($data['FCC_fk_FMC']);
      $Comment->setId($data['FCC_id']);
      $Comment->setContenu($data['FCC_content']);
      $Comment->setNewsId($data['FCC_fk_FNC']);
      $Comment->setDateAjout(new \DateTime($data['FCC_dateadd']));
      $Comment->setDateModif(new \DateTime($data['FCC_dateupdate']));
      $Comment->News = new News(array(
        'titre' => $data['FNC_title']
      ));

      $Comments_a[]=$Comment;
    }

    return $Comments_a;
  }

  public function getListOf($newsId)
  {
    if (!is_int($newsId))
    {
      throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
    }
    
    $q = $this->dao->prepare('SELECT *
                              FROM t_for_commentc
                              INNER JOIN t_for_memberc ON FCC_fk_FMC = FMC_id
                              WHERE FCC_fk_FNC = :newsId
                              ORDER BY FCC_id DESC');

    $q->bindValue(':newsId', $newsId, \PDO::PARAM_INT);
    $q->execute();
    
    $comments = array();

    while($data = $q->fetch())
    {
      $Comment = new Comment();
      $Comment->setAuteurId($data['FCC_fk_FMC']);
      $Comment->setId($data['FCC_id']);
      $Comment->setContenu($data['FCC_content']);
      $Comment->setNewsId($data['FCC_fk_FNC']);
      $Comment->setDateAjout(new \DateTime($data['FCC_dateadd']));
      $Comment->setDateModif(new \DateTime($data['FCC_dateupdate']));
      $Comment->setMembre(new Member(array('login'=>$data['FMC_login'], 'id'=>$data['FMC_id'])));

      $comments[]=$Comment;
    }

    return $comments;
  }

  public function getFiveLast($last_insert_id, $news_id)
  {
    $sql = 'SELECT *
            FROM t_for_commentc
            INNER JOIN t_for_memberc ON FCC_fk_FMC = FMC_id
            WHERE FCC_fk_FNC = :news_id AND FCC_id > :last_insert_id
            ORDER BY FCC_id';


    $requete = $this->dao->prepare($sql);

    $requete->bindValue('last_insert_id', (int) $last_insert_id, \PDO::PARAM_INT);
    $requete->bindValue('news_id', $news_id, \PDO::PARAM_INT);
    $requete->execute();

    $Comments_a = array();

    while($data = $requete->fetch())
    {
      $Comment = new Comment();
      $Comment->setAuteurId($data['FCC_fk_FMC']);
      $Comment->setId($data['FCC_id']);
      $Comment->setContenu($data['FCC_content']);
      $Comment->setNewsId($data['FCC_fk_FNC']);
      $Comment->setDateAjout(new \DateTime($data['FCC_dateadd']));
      $Comment->setDateModif(new \DateTime($data['FCC_dateupdate']));
      $Comment->setMembre(new Member(array('login'=>$data['FMC_login'], 'id'=>$data['FMC_id'])));

      $Comments_a[]=$Comment;
    }

    return $Comments_a;
  }

  public function getUnique($comment_id)
  {
    $sql = 'SELECT *
            FROM t_for_commentc
            INNER JOIN t_for_memberc ON FCC_fk_FMC = FMC_id
            WHERE FCC_id = :comment_id';

    $requete = $this->dao->prepare($sql);

    $requete->bindValue('comment_id', $comment_id, \PDO::PARAM_INT);
    $requete->execute();

    $Comment = new Comment();

   while($data = $requete->fetch())
   {
     $Comment->setAuteurId($data['FCC_fk_FMC']);
     $Comment->setId($data['FCC_id']);
     $Comment->setContenu($data['FCC_content']);
     $Comment->setNewsId($data['FCC_fk_FNC']);
     $Comment->setDateAjout(new \DateTime($data['FCC_dateadd']));
     $Comment->setDateModif(new \DateTime($data['FCC_dateupdate']));
     $Comment->setMembre(new Member(array('login'=>$data['FMC_login'], 'id'=>$data['FMC_id'])));
   }

    if($Comment->id())
    {
      return $Comment;
    }

    return false;
  }

  protected function modify(Comment $comment)
  {
    $q = $this->dao->prepare('UPDATE t_for_commentc SET FCC_fk_FMC = :auteurId, FCC_content = :contenu, FCC_dateupdate = NOW() WHERE FCC_id = :id');
    
    $q->bindValue(':auteurId', $comment->auteurId(), \PDO::PARAM_INT);
    $q->bindValue(':contenu', $comment->contenu(), \PDO::PARAM_STR);
    $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);
    
    $q->execute();
  }
  
  public function get($id)
  {
    $q = $this->dao->prepare('SELECT FCC_id as id, FCC_fk_FNC as newsId, FCC_fk_FMC as auteurId, FCC_content as contenu FROM t_for_commentc WHERE FCC_id = :id');
    $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $q->execute();
    
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
    
    return $q->fetch();
  }

  public function delete($id)
  {
    $sql = 'DELETE FROM t_for_commentc WHERE FCC_id = :id';

    $requete = $this->dao->prepare($sql);
    $requete->bindValue('id', $id, \PDO::PARAM_INT);
    $requete->execute();
  }

  public function deleteFromNews($newsId)
  {
    $sql = 'DELETE FROM t_for_commentc WHERE FCC_fk_FNC = :newsId';

    $requete = $this->dao->prepare($sql);
    $requete->bindValue('newsId', $newsId, \PDO::PARAM_INT);
    $requete->execute();
  }

  public function countUsingMemberId($member_id)
  {
    $sql = 'SELECT COUNT(*)
            FROM T_FOR_commentc
            WHERE FCC_fk_FMC = :member_id';

    $requete = $this->dao->prepare($sql);
    $requete->bindValue('member_id', (int) $member_id, \PDO::PARAM_INT);
    $requete->execute();

    return $requete->fetchColumn();
  }

  public function countUsingNewsId($news_id)
  {
    $sql = 'SELECT COUNT(*)
            FROM T_FOR_commentc
            WHERE FCC_fk_FNC = :news_id';

    $requete = $this->dao->prepare($sql);
    $requete->bindValue(':news_id', (int) $news_id, \PDO::PARAM_INT);
    $requete->execute();

    return $requete->fetchColumn();
  }
}