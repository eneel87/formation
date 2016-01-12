<?php
namespace Entity;

use \OCFram\Entity;
use \Entity\Member;

class Comment extends Entity
{
  protected $newsId,
            $auteurId,
            $contenu,
            $dateAjout,
            $dateModif,
            $Membre;

  const AUTEUR_INVALIDE = 1;
  const CONTENU_INVALIDE = 2;

  public function isValid()
  {
    return !(empty($this->auteurId) || empty($this->contenu));
  }

  public function setNewsId($newsId)
  {
    $this->newsId = (int) $newsId;
  }

  public function setAuteurId($auteurId)
  {
    $auteurId = (int) $auteurId;

    if (!is_int($auteurId) || empty($auteurId))
    {
      $this->erreurs[] = self::AUTEUR_INVALIDE;
    }

    $this->auteurId = $auteurId;
  }

  public function setContenu($contenu)
  {
    if (!is_string($contenu) || empty($contenu))
    {
      $this->erreurs[] = self::CONTENU_INVALIDE;
    }

    $this->contenu = $contenu;
  }

  public function setDateAjout(\DateTime $dateAjout)
  {
    $this->dateAjout = $dateAjout;
  }

  public function setDateModif(\DateTime $dateModif)
  {
    $this->dateModif = $dateModif;
  }

  public function setMembre(Member $Membre)
  {
    $this->Membre = $Membre;
  }

  public function newsId()
  {
    return $this->newsId;
  }

  public function auteurId()
  {
    return $this->auteurId;
  }

  public function contenu()
  {
    return $this->contenu;
  }

  public function dateAjout()
  {
    return $this->dateAjout;
  }

  public function dateModif()
  {
    return $this->dateModif;
  }

  public function Membre()
  {
    return $this->Membre;
  }
}