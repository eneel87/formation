<?php
namespace Entity;

use \OCFram\Entity;
use \Entity\Member;

class News extends Entity
{
  protected $auteurId,
            $titre,
            $contenu,
            $dateAjout,
            $dateModif;
            //$Membre;

  const AUTEUR_INVALIDE = 1;
  const TITRE_INVALIDE = 2;
  const CONTENU_INVALIDE = 3;

  public function isValid()
  {
    return !(empty($this->auteurId) || empty($this->titre) || empty($this->contenu));
  }


  // SETTERS //

  public function setAuteurId($id)
  {
    if (!is_int($id) || empty($id))
    {
      $this->erreurs[] = self::AUTEUR_INVALIDE;
    }

    $this->auteurId = $id;
  }

  public function setTitre($titre)
  {
    if (!is_string($titre) || empty($titre))
    {
      $this->erreurs[] = self::TITRE_INVALIDE;
    }

    $this->titre = $titre;
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

  /*public function setMembre(Member $Membre)
  {
    $this->Membre = $Membre;
  }*/

  // GETTERS //

  public function auteurId()
  {
    return $this->auteurId;
  }

  public function titre()
  {
    return $this->titre;
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

  /*public function membre()
  {
    return $this->Membre;
  }*/
}