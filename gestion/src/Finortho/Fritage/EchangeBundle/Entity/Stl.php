<?php

namespace Finortho\Fritage\EchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Stl
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @ExclusionPolicy("all")
 */
class Stl
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    private $id;

    /**
     * @var string vatialbe stockant l'extenstion de l'image
     *
     * @ORM\Column(name="url", type="string", length=255)
     * @Expose
     */
    private $url;

    /**
     * @var string vatiable stockant le nom de l'image
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    private $name;

    /**
     * @var string date d'upload du ficher
     *
     * @ORM\Column(name="date", type="datetime", length=255)
     * @Expose
     */
    private $date;

    /**
     * @var $utilisateur Utilisateur attaché à la piece
     *
     * @ORM\ManyToOne(targetEntity="Finortho\Fritage\EchangeBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;

    /**
     * @var string $commentaire Commentaire du client sur la pièce à produire
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @var string angle de la pièce ( remplacement du Z )
     *
     * @ORM\Column(name="axis", type="string", length=155, nullable=true)
     */
    private $axis;

    /**
     * @var integer quantité de pièces sur ce modèle
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     * @Expose
     */
    private $quantite;

    /**
     * @var pack auquel appartient la pièce
     *
     * @ORM\ManyToOne(targetEntity="Finortho\Fritage\EchangeBundle\Entity\PackItem")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @Expose
     */
    private $pack;

    /**
     * @ORM\ManyToOne(targetEntity="Finortho\Fritage\EchangeBundle\Entity\Commande", inversedBy="stls")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $commande;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * Permet de définir l'extension du fichier
     *
     * @param string $url
     *
     * @return Stl
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     *
     * Permet de récupérer l'extenstion du fichier
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * Variable allant stocker l'image
     */
    private $file;

    // On ajoute cet attribut pour y stocker le nom du fichier temporairement
    /**
     * @var string $tempFilename Variable permettant de stocker temporairement le nom de l'image
     */
    private $tempFilename;

    /**
     * get file
     *
     * Fonction permettant de récupérer le fichier
     *
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    // On modifie le setter de File, pour prendre en compte l'upload d'un fichier lorsqu'il en existe déjà un autre
    /**
     * set File
     *
     * Fonction permetttant de renseigner l'image
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        // On vérifie si on avait déjà un fichier pour cette entité
        if (null !== $this->url) {
            // On sauvegarde l'extension du fichier pour le supprimer plus tard
            $this->tempFilename = $this->url;

            // On réinitialise les valeurs des attributs url et alt
            $this->url = null;
            $this->alt = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }
        // Le nom du fichier est son id, on doit juste stocker également son extension
        // Pour faire propre, on devrait renommer cet attribut en « extension », plutôt que « url »
        $this->url = 'stl';
    }

    /**
     * upload
     *
     * Pour uploader l'image sur le serveur
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif)
        if (null === $this->file) {
            return;
        }

        // Si on avait un ancien fichier, on le supprime
        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir() . '/' . $this->name . '.' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $this->name = $this->renomme($this->name);

        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move(
            $this->getUploadRootDir(), // Le répertoire de destination
            $this->name . '.' . $this->url   // Le nom du fichier à créer, ici « id.extension »
        );


    }

    /**
     * PreRemove upload
     *
     * Fonction permettan de suvergarder le nom du fichier
     *
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        // On sauvegarde temporairement le nom du fichier, car il dépend de l'id
        $this->tempFilename = $this->getUploadRootDir() . '/' . $this->id . '.' . $this->url;
    }

    /**
     * Remove upload
     *
     * Permet d'enlever le fichier sur le serveur
     *
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        // En PostRemove, on n'a pas accès à l'id, on utilise notre nom sauvegardé
        if (file_exists($this->tempFilename)) {
            // On supprime le fichier
            unlink($this->tempFilename);
        }
    }

    /**
     * Récupération du dossier d'upload
     *
     * Permet de récupérer le dossier d'upload des images à partir de web
     *
     * @return string
     */
    public function getUploadDir()
    {

        // On retourne le chemin relatif vers l'image pour un navigateur
        return 'image/stl/' . $this->getUtilisateur()->getUsernameCanonical() . '/' . $this->getDate()->format('d-m-Y');
    }

    /**
     * Récupéréation du dossier d'upload en absolu
     *
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return $this->getUploadDir();
    }

    /**
     * Récupération du fichier depuis web
     *
     * @return string
     */
    public function getWebPath()
    {
        return $this->getUploadDir() . '/' . $this->getName() . '.' . $this->getUrl();
    }

    /**
     * Récupération du fichier en absolu
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->getUploadRootDir() . '/' . $this->getName() . '.' . $this->getUrl();
    }

    /**
     * Récupération du fichier pour le rendu 3D
     *
     * @return string
     */
    public function get3DPath()
    {
        return '../' . $this->getUploadDir() . '/' . $this->getName() . '.' . $this->getUrl();
    }

    /**
     * Conversion d'une chaine de caractère ( enlève majuscule, espace, et caractères accentues )
     *
     * @param $string Chaine de caractères à convertir
     * @return mixed|string Chaine de caractères convertie
     */
    public function renomme($string)
    {
        $string = strtolower(htmlentities($string, ENT_QUOTES, 'utf-8'));
        $string = preg_replace('#\&(.)(?:uml|circ|tilde|acute|grave|cedil|ring)\;#', '\1', $string);
        $string = preg_replace('#\&(.{2})(?:lig)\;#', '\1', $string); // pour '&oelig;'...
        $string = preg_replace('#\&[a-z]+\;#', '', $string);
        $string = preg_replace('# #', "_", $string);

        return $string;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Stl
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Stl
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set utilisateur
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\User $utilisateur
     * @return Stl
     */
    public function setUtilisateur(\Finortho\Fritage\EchangeBundle\Entity\User $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \Finortho\Fritage\EchangeBundle\Entity\User 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set axis
     *
     * @param string $axis
     * @return Stl
     */
    public function setAxis($axis)
    {
        $this->axis = $axis;

        return $this;
    }

    /**
     * Get axis
     *
     * @return string 
     */
    public function getAxis()
    {
        return $this->axis;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     * @return Stl
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer 
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Stl
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set pack
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\PackItem $pack
     * @return Stl
     */
    public function setPack(\Finortho\Fritage\EchangeBundle\Entity\PackItem $pack = null)
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * Get pack
     *
     * @return \Finortho\Fritage\EchangeBundle\Entity\PackItem 
     */
    public function getPack()
    {
        return $this->pack;
    }


    /**
     * Set commande
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\Commande $commande
     * @return Stl
     */
    public function setCommande(\Finortho\Fritage\EchangeBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \Finortho\Fritage\EchangeBundle\Entity\Stl 
     */
    public function getCommande()
    {
        return $this->commande;
    }
}
