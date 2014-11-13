<?php
/**
 * AnimeDb package
 *
 * @package   AnimeDb
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace AnimeDb\Bundle\DevBundle\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AnimeDb\Bundle\CatalogBundle\Entity\Storage;
use AnimeDb\Bundle\CatalogBundle\Entity\Item;
use AnimeDb\Bundle\CatalogBundle\Entity\Name;
use AnimeDb\Bundle\CatalogBundle\Entity\Source;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141012160751 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * Filesystem
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fs;

    /**
     * Entity manager
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $em;

    /**
     * Target dir
     *
     * @var string
     */
    protected $target;

    /**
     * Source dir
     *
     * @var string
     */
    protected $source;

    /**
     * Set container
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->abortIf(is_null($container), 'Requires DI container');

        $this->fs = $container->get('filesystem');
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->target = $container->getParameter('kernel.root_dir').'/../web/media/example/';
        $this->source = $container->get('kernel')->locateResource('@AnimeDbDevBundle/Resource/private/images/');
    }

    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Migrations\AbstractMigration::up()
     */
    public function up(Schema $schema)
    {
        // copy images for example items
        $this->fs->mirror($this->source, $this->target);
        $storage = $this->em->getRepository('AnimeDbCatalogBundle:Storage')->findBy([], [], 1);

        $this->persist($this->getOnePiece($storage));
        $this->em->flush();
    }
    /**
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Migrations\AbstractMigration::down()
     */
    public function down(Schema $schema)
    {
        // remove images for example items
        $this->fs->remove($this->target);
    }

    /**
     * Persist item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Item $item
     */
    protected function persist(Item $item)
    {
        $this->em->persist($item);
        foreach ($item->getNames() as $name) {
            $this->em->persist($name);
        }
        foreach ($item->getSources() as $source) {
            $this->em->persist($source);
        }
    }

    /**
     * Get country
     *
     * @param string $name
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Country
     */
    protected function getCountry($name)
    {
        return $this->em->getRepository('AnimeDbCatalogBundle:Country')->find($name);
    }

    /**
     * Get type
     *
     * @param string $id
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Type
     */
    protected function getType($id)
    {
        return $this->em->getRepository('AnimeDbCatalogBundle:Type')->find($id);
    }

    /**
     * Get studio
     *
     * @param string $name
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Studio
     */
    protected function getStudio($name)
    {
        return $this->em->getRepository('AnimeDbCatalogBundle:Studio')->findBy(['name' => $name]);
    }

    /**
     * Get genre
     *
     * @param string $name
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Genre
     */
    protected function getGenre($name)
    {
        return $this->em->getRepository('AnimeDbCatalogBundle:Genre')->findBy(['name' => $name]);
    }

    /**
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getOnePiece(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/one-piece.jpg')
            ->setDatePremiere(new \DateTime('1999-10-20'))
            ->setDuration(25)
            ->setEpisodesNumber('602+')
            ->setFileInfo('+ 6 спэшлов')
            ->setName('One Piece')
            ->setPath($storage->getPath().'One Piece (2011) [TV]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Последние слова, произнесенные Королем Пиратов перед казнью, вдохновили многих: «Мои сокровища? Коли '
                .'хотите, забирайте. Ищите – я их все оставил там!». Легендарная фраза Золотого Роджера ознаменовала '
                .'начало Великой Эры Пиратов – тысячи людей в погоне за своими мечтами отправились на Гранд Лайн, '
                .'самое опасное место в мире, желая стать обладателями мифических сокровищ... Но с каждым годом '
                .'романтиков становилось все меньше, их постепенно вытесняли прагматичные пираты-разбойники, которым '
                .'награбленное добро было куда ближе, чем какие-то «никчемные мечты». Но вот, одним прекрасным днем, '
                .'семнадцатилетний Монки Д. Луффи исполнил заветную мечту детства - отправился в море. Его цель - ни '
                .'много, ни мало стать новым Королем Пиратов. За достаточно короткий срок юному капитану удается '
                .'собрать команду, состоящую из не менее амбициозных искателей приключений. И пусть ими движут '
                .'совершенно разные устремления, главное, этим ребятам важны не столько деньги и слава, сколько куда '
                .'более ценное – принципы и верность друзьям. И еще – служение Мечте. Что ж, пока по Гранд Лайн '
                .'плавают такие люди, Великая Эра Пиратов всегда будет с нами!'
            )
            ->setStudio($this->getStudio('Toei Animation'))
            ->setType($this->getType('tv'))
            ->addGenre($this->getGenre('Adventure'))
            ->addGenre($this->getGenre('Comedy'))
            ->addGenre($this->getGenre('Senen'))
            ->addGenre($this->getGenre('Fantasy'))
            ->addName((new Name())->setName('Ван-Пис'))
            ->addName((new Name())->setName('Одним куском'))
            ->addName((new Name())->setName('ワンピース'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=836'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=69'))
            ->addSource((new Source())->setUrl('http://myanimelist.net/anime/21/'))
            ->addSource((new Source())->setUrl('http://cal.syoboi.jp/tid/350/time'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=162790'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/One_Piece'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/One_Piece'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/ONE_PIECE_%28%E3%82%A2%E3%83%8B%E3%83%A1%29'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=731'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=803'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/21-one-piece'));
    }
}
