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
use AnimeDb\Bundle\AppBundle\Util\Filesystem;
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

        // create storage
        $storage = (new Storage())
            ->setDescription('Storage on local computer')
            ->setName('Local')
            ->setPath(Filesystem::getUserHomeDir())
            ->setType(Storage::TYPE_FOLDER);
        $this->em->persist($storage);

        // create items
        $this->persist($this->getItemOnePiece($storage));
        $this->persist($this->getItemSamuraiChamploo($storage));
        $this->persist($this->getItemFullmetalAlchemist($storage));
        $this->persist($this->getItemSpiritedAway($storage));
        $this->persist($this->getItemGreatTeacherOnizuka($storage));
        $this->persist($this->getItemBeck($storage));
        $this->persist($this->getItemSamuraiXTrustAndBetrayal($storage));
        $this->persist($this->getItemMyNeighborTotoro($storage));
        $this->persist($this->getItemHellsing($storage));
        $this->persist($this->getItemGintama($storage));
        $this->persist($this->getItemBakuman($storage));
        $this->persist($this->getItemTengenToppaGurrenLagann($storage));
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
     * Get One Piece item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemOnePiece(Storage $storage)
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
            ->addGenre($this->getGenre('Shounen'))
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

    /**
     * Get Samurai Champloo item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemSamuraiChamploo(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/samurai-champloo.jpg')
            ->setDateEnd(new \DateTime('2005-03-19'))
            ->setDatePremiere(new \DateTime('2004-05-20'))
            ->setDuration(25)
            ->setEpisodes(
'1. Tempestuous Temperaments (20.05.2004, 25 мин.)
2. Redeye Reprisal (03.06.2004, 25 мин.)
3. Hellhounds for Hire (Part 1) (10.06.2004, 25 мин.)
4. Hellhounds for Hire (Part 2) (17.06.2004, 25 мин.)
5. Artistic Anarchy (24.06.2004, 25 мин.)
6. Stranger Searching (01.07.2004, 25 мин.)
7. A Risky Racket (08.07.2004, 25 мин.)
8. The Art of Altercation (15.07.2004, 25 мин.)
9. Beatbox Bandits (22.07.2004, 25 мин.)
10. Lethal Lunacy (29.07.2004, 25 мин.)
11. Gamblers and Gallantry (05.08.2004, 25 мин.)
12. The Disorder Diaries (12.08.2004, 25 мин.)
13. Misguided Miscreants (Part 1) (26.08.2004, 25 мин.)
14. Misguided Miscreants (Part 2) (02.09.2004, 25 мин.)
15. Bogus Booty (09.09.2004, 25 мин.)
16. Lullabies of The Lost (Verse 1) (16.09.2004, 25 мин.)
17. Lullabies of The Lost (Verse 2) (23.09.2004, 25 мин.)
18. War of The Words (22.01.2005, 25 мин.)
19. Unholy Union (29.01.2005, 25 мин.)
20. Elegy of Entrapment (Verse 1) (05.02.2005, 25 мин.)
21. Elegy of Entrapment (Verse 2) (12.02.2005, 25 мин.)
22. Cosmic Collisions (19.02.2005, 25 мин.)
23. Baseball Blues (26.02.2005, 25 мин.)
24. Evanescent Encounter (Part 1) (05.03.2005, 25 мин.)
25. Evanescent Encounter (Part 2) (12.03.2005, 25 мин.)
26. Evanescent Encounter (Part 3) (19.03.2005, 25 мин.)'
            )
            ->setEpisodesNumber('26')
            ->setName('Samurai Champloo')
            ->setPath($storage->getPath().'Samurai Champloo (2004) [TV]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Потеряв маму, юная Фуу год проработала в чайной, а потом решила отправиться на поиски человека, '
                .'который, кажется, виновен во всех её несчастьях. У Фуу была надёжная примета: это самурай, '
                .'пахнущий подсолнухами. Но как выжить в Японии эпохи Эдо, когда за каждым поворотом – бандиты, '
                .'которые могут тебя похитить и продать в бордель, а единственный друг – ручная белка-летяга? Фуу '
                .'повезло: она встретила двух юных и при этом весьма сноровистых бойцов – бывшего пирата Мугэна и '
                .'ронина Дзина. Заручившись их поддержкой, девушка отправилась в путь через всю страну. Не важно, что '
                .'в животе всё время бурчит, и нет ни денег, ни документов – зато есть несравненные способности '
                .'ввязываться в неприятности! При первой встрече Мугэн и Дзин попытались выяснить, кто из них круче – '
                .'и они готовы продолжить дуэль при первой возможности, однако главная проблема в том, что у каждого '
                .'из путешественников своё прошлое и опасные враги, о которых они даже не подозревают. И неизвестно '
                .'ещё, у кого этих врагов и старых грехов больше – у пирата, грабившего корабли, у ронина, убившего '
                .'своего учителя, или у девушки-сиротки?'.PHP_EOL
                .'Автор знаменитого Cowboy Bebop Синъитиро Ватанабэ смешал стильный коктейль из катан и хип-хопа. В '
                .'его сериале прошлое сталкивается с будущим, Восток – с Западом, герои классического кино – с '
                .'реальными историческими персонажами. Но все эти забористые ингредиенты лишь оттеняют историю о трёх '
                .'разных людях, которых свела и сроднила долгая дорога...'
            )
            ->setStudio($this->getStudio('Manglobe'))
            ->setType($this->getType('tv'))
            ->addGenre($this->getGenre('Adventure'))
            ->addGenre($this->getGenre('Comedy'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('Samurai'))
            ->addName((new Name())->setName('Самурай Чамплу'))
            ->addName((new Name())->setName('サムライチャンプルー'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=2636'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=1543'))
            ->addSource((new Source())->setUrl('http://myanimelist.net/anime/205/'))
            ->addSource((new Source())->setUrl('http://cal.syoboi.jp/tid/395/time'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=319278'))
            ->addSource((new Source())->setUrl('http://wiki.livedoor.jp/radioi_34/d/%a5%b5%a5%e0%a5%e9%a5%a4%a5%c1%a5%e3%a5%f3%a5%d7%a5%eb%a1%bc'))
            ->addSource((new Source())->setUrl('http://www1.vecceed.ne.jp/~m-satomi/SAMURAICHANPLOO.html'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Samurai_Champloo'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/%D0%A1%D0%B0%D0%BC%D1%83%D1%80%D0%B0%D0%B9_%D0%A7%D0%B0%D0%BC%D0%BF%D0%BB%D1%83'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/%E3%82%B5%E3%83%A0%E3%83%A9%E3%82%A4%E3%83%81%E3%83%A3%E3%83%B3%E3%83%97%E3%83%AB%E3%83%BC'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=361'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=2699'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/205-samurai-champloo'));
    }

    /**
     * Get Fullmetal Alchemist item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemFullmetalAlchemist(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/fullmetal-alchemist.jpg')
            ->setDateEnd(new \DateTime('2004-10-02'))
            ->setDatePremiere(new \DateTime('2003-10-04'))
            ->setDuration(25)
            ->setEpisodes(
'1. To Challenge the Sun (04.10.2003, 25 мин.)
2. Body of the Sanctioned (11.10.2003, 25 мин.)
3. Mother (18.10.2003, 25 мин.)
4. A Forger`s Love (25.10.2003, 25 мин.)
5. The Man with the Mechanical Arm (01.11.2003, 25 мин.)
6. The Alchemy Exam (08.11.2003, 25 мин.)
7. Night of the Chimera`s Cry (15.11.2003, 25 мин.)
8. The Philosopher`s Stone (22.11.2003, 25 мин.)
9. Be Thou for the People (29.11.2003, 25 мин.)
10. The Phantom Thief (06.12.2003, 25 мин.)
11. The Other Brothers Elric, Part 1 (13.12.2003, 25 мин.)
12. The Other Brothers Elric, Part 2 (20.12.2003, 25 мин.)
13. Fullmetal vs. Flame (27.12.2003, 25 мин.)
14. Destruction`s Right Hand (10.01.2004, 25 мин.)
15. The Ishbal Massacre (17.01.2004, 25 мин.)
16. That Which Is Lost (24.01.2004, 25 мин.)
17. House of the Waiting Family (31.01.2004, 25 мин.)
18. Marcoh`s Notes (07.02.2004, 25 мин.)
19. The Truth Behind Truths (14.02.2004, 25 мин.)
20. Soul of the Guardian (21.02.2004, 25 мин.)
21. The Red Glow (28.02.2004, 25 мин.)
22. Created Human (06.03.2004, 25 мин.)
23. Fullmetal Heart (13.03.2004, 25 мин.)
24. Bonding Memories (20.03.2004, 25 мин.)
25. Words of Farewell (27.03.2004, 25 мин.)
26. Her Reason (03.04.2004, 25 мин.)
27. Teacher (10.04.2004, 25 мин.)
28. All is One, One is All (17.04.2004, 25 мин.)
29. The Untainted Child (24.04.2004, 25 мин.)
30. Assault on South Headquarters (01.05.2004, 25 мин.)
31. Sin (08.05.2004, 25 мин.)
32. Dante of the Deep Forest (15.05.2004, 25 мин.)
33. Al, Captured (29.05.2004, 25 мин.)
34. Theory of Avarice (05.06.2004, 25 мин.)
35. Reunion of the Fallen (12.06.2004, 25 мин.)
36. The Sinner Within (19.06.2004, 25 мин.)
37. The Flame Alchemist, the Bachelor Lieutenant and the Mystery of Warehouse 13 (26.06.2004, 25 мин.)
38. With the River`s Flow (03.07.2004, 25 мин.)
39. Secret of Ishbal (10.07.2004, 25 мин.)
40. The Scar (17.07.2004, 25 мин.)
41. Holy Mother (24.07.2004, 25 мин.)
42. His Name is Unknown (24.07.2004, 25 мин.)
43. The Stray Dog (31.07.2004, 25 мин.)
44. Hohenheim of Light (07.08.2004, 25 мин.)
45. A Rotted Heart (21.08.2004, 25 мин.)
46. Human Transmutation (28.08.2004, 25 мин.)
47. Sealing the Homunculus (04.09.2004, 25 мин.)
48. Goodbye (11.09.2004, 25 мин.)
49. The Other Side of the Gate (18.09.2004, 25 мин.)
50. Death (25.09.2004, 25 мин.)
51. Laws and Promises (02.10.2004, 25 мин.)'
            )
            ->setEpisodesNumber('51')
            ->setFileInfo('+ спэшл')
            ->setName('Fullmetal Alchemist')
            ->setPath($storage->getPath().'Fullmetal Alchemist (2003) [TV]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Они нарушили основной закон алхимии и жестоко за это поплатились. И теперь два брата странствуют по '
                .'миру в поисках загадочного философского камня, который поможет им исправить содеянное… Это мир, в '
                .'котором вместо науки властвует магия, в котором люди способны управлять стихиями. Но у магии тоже '
                .'есть законы, которым нужно следовать. В противном случае расплата будет жестокой и страшной. Два '
                .'брата - Эдвард и Альфонс Элрики - пытаются совершить запретное: воскресить умершую мать. Однако '
                .'закон равноценного обмена гласит: чтобы что-то получить, ты должен отдать нечто равноценное…'
            )
            ->setStudio($this->getStudio('Bones'))
            ->setType($this->getType('tv'))
            ->addGenre($this->getGenre('Adventure'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('Shounen'))
            ->addGenre($this->getGenre('Fantasy'))
            ->addName((new Name())->setName('Стальной алхимик'))
            ->addName((new Name())->setName('Hagane no Renkin Jutsushi'))
            ->addName((new Name())->setName('Hagane no Renkinjutsushi'))
            ->addName((new Name())->setName('Full Metal Alchemist'))
            ->addName((new Name())->setName('Hagaren'))
            ->addName((new Name())->setName('鋼の錬金術師'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=2960'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=979'))
            ->addSource((new Source())->setUrl('http://cal.syoboi.jp/tid/134/time'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=241943'))
            ->addSource((new Source())->setUrl('http://www1.vecceed.ne.jp/~m-satomi/FULLMETALALCHEMIST.html'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Fullmetal_Alchemist'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/Fullmetal_Alchemist'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/%E9%8B%BC%E3%81%AE%E9%8C%AC%E9%87%91%E8%A1%93%E5%B8%AB_%28%E3%82%A2%E3%83%8B%E3%83%A1%29'))
            ->addSource((new Source())->setUrl('http://oboi.kards.ru/?act=search&level=6&search_str=FullMetal%20Alchemist'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=124'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=2368'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/121-fullmetal-alchemist'));
    }

    /**
     * Get Spirited Away item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemSpiritedAway(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/spirited-away.jpg')
            ->setDatePremiere(new \DateTime('2001-07-20'))
            ->setDuration(125)
            ->setEpisodesNumber('1')
            ->setName('Spirited Away')
            ->setPath($storage->getPath().'Spirited Away (2001)'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Маленькая Тихиро вместе с мамой и папой переезжают в новый дом. Заблудившись по дороге, они '
                .'оказываются в странном пустынном городе, где их ждет великолепный пир. Родители с жадностью '
                .'набрасываются на еду и к ужасу девочки превращаются в свиней, став пленниками злой колдуньи Юбабы, '
                .'властительницы таинственного мира древних богов и могущественных духов. Теперь, оказавшись одна '
                .'среди магических существ и загадочных видений, отважная Тихиро должна придумать, как избавить своих '
                .'родителей от чар коварной старухи и спастись из пугающего царства призраков...'
            )
            ->setStudio($this->getStudio('Studio Ghibli'))
            ->setType($this->getType('feature'))
            ->addGenre($this->getGenre('Adventure'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('Fable'))
            ->addName((new Name())->setName('Унесённые призраками'))
            ->addName((new Name())->setName('Sen to Chihiro no Kamikakushi'))
            ->addName((new Name())->setName('千と千尋の神隠し'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=377'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=112'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=163027'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Spirited_Away'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/%D0%A3%D0%BD%D0%B5%D1%81%D1%91%D0%BD%D0%BD%D1%8B%D0%B5_%D0%BF%D1%80%D0%B8%D0%B7%D1%80%D0%B0%D0%BA%D0%B0%D0%BC%D0%B8'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/%E5%8D%83%E3%81%A8%E5%8D%83%E5%B0%8B%E3%81%AE%E7%A5%9E%E9%9A%A0%E3%81%97'))
            ->addSource((new Source())->setUrl('http://oboi.kards.ru/?act=search&level=6&search_str=Spirited%20Away'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=368'))
            ->addSource((new Source())->setUrl('http://uanime.org.ua/anime/38.html'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=87'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/199-sen-to-chihiro-no-kamikakushi'));
    }

    /**
     * Get Great Teacher Onizuka item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemGreatTeacherOnizuka(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/gto.jpg')
            ->setDateEnd(new \DateTime('2000-09-17'))
            ->setDatePremiere(new \DateTime('1999-06-30'))
            ->setDuration(25)
            ->setEpisodes(
'1. GTO - The Legend Begins (30.06.1999, 45 мин.)
2. Enter Uchiyamada (07.07.1999, 25 мин.)
3. Late Night Roof Diving (21.07.1999, 25 мин.)
4. The Secret Life of Onizuka (11.08.1999, 25 мин.)
5. GTO - An Eye for an Eye, a Butt for a Butt (18.08.1999, 25 мин.)
6. Conspiracies All Around (25.08.1999, 25 мин.)
7. The Mother of All Crushes (01.09.1999, 25 мин.)
8. Bungee Jumping Made Easy (08.09.1999, 25 мин.)
9. Onizuka and The Art of War (22.09.1999, 25 мин.)
10. Outside Looking In (22.09.1999, 25 мин.)
11. To Be Idolized by a Nation (17.10.1999, 25 мин.)
12. The Formula for Treachery (31.10.1999, 25 мин.)
13. Only the Best Will Do (21.11.1999, 25 мин.)
14. Between a Rock and a Hard Place (05.12.1999, 25 мин.)
15. The Great Sacrifice (12.12.1999, 25 мин.)
16. Beauty + Brains = A Dangerous Mix (19.12.1999, 25 мин.)
17. Falling for The Great Onizuka (16.01.2000, 25 мин.)
18. How to Dine and Dash (23.01.2000, 25 мин.)
19. Private Investigations (30.01.2000, 25 мин.)
20. Love Letters (06.02.2000, 25 мин.)
21. Revolution Everywhere (13.02.2000, 25 мин.)
22. The Art of Demolition (20.02.2000, 25 мин.)
23. Superstition (27.02.2000, 25 мин.)
24. Compromising Positions (05.03.2000, 25 мин.)
25. Playing Doctor - GTO Style (12.03.2000, 25 мин.)
26. Onizuka Meets His Match (19.03.2000, 25 мин.)
27. GTO - Agent to the Stars (02.04.2000, 25 мин.)
28. Whatever Can Go Wrong, Will Go Wrong (16.04.2000, 25 мин.)
29. Studies in High Finance (23.04.2000, 25 мин.)
30. Money Talks, GTO Walks (30.04.2000, 25 мин.)
31. Destination: Okinawa (07.05.2000, 25 мин.)
32. The Law of Probability (14.05.2000, 25 мин.)
33. Search and Rescue (28.05.2000, 25 мин.)
34. Good Cop / Bad Cop (04.06.2000, 25 мин.)
35. Wedding Bell Blues (11.06.2000, 25 мин.)
36. Self-Improvement: Fuyutsuki`s Transformation (18.06.2000, 25 мин.)
37. Living Together (16.07.2000, 25 мин.)
38. Great Treasure Onizuka (30.07.2000, 25 мин.)
39. Alone in the Dark (13.08.2000, 25 мин.)
40. Matters of the Heart (20.08.2000, 25 мин.)
41. Confessions (27.08.2000, 25 мин.)
42. Old Wounds Revisited (10.09.2000, 25 мин.)
43. Onizuka`s Final Battle (17.09.2000, 25 мин.)'
            )
            ->setEpisodesNumber('43')
            ->setFileInfo('+ 2 эп.-коллажа')
            ->setName('Great Teacher Onizuka')
            ->setPath($storage->getPath().'GTO (1999) [TV]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Онидзука Эйкити («22 года, холост», - как он сам любит представляться) - настоящий ужас на двух '
                .'колесах, член нагоняющей ужас на горожан банды мотоциклистов, решает переквалифицироваться в… '
                .'школьного учителя. Ведь в любом учебном заведении полным-полно аппетитных старшеклассниц в '
                .'коротеньких юбочках! Но чем глубже примеривший необычную роль хулиган окунается в перипетии общего '
                .'образования, тем сильнее он пытается переиначить место работы на свой манер - одерживая одну за '
                .'другой победы над царящими в школе косностью, лицемерием, показухой и безразличием.'
            )
            ->setStudio($this->getStudio('Pierrot'))
            ->setType($this->getType('tv'))
            ->addGenre($this->getGenre('Comedy'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('School'))
            ->addName((new Name())->setName('Крутой учитель Онидзука'))
            ->addName((new Name())->setName('GTO'))
            ->addName((new Name())->setName('グレート・ティーチャー・オニヅカ'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=153'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=191'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=125613'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Great_Teacher_Onizuka'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/%D0%9A%D1%80%D1%83%D1%82%D0%BE%D0%B9_%D1%83%D1%87%D0%B8%D1%82%D0%B5%D0%BB%D1%8C_%D0%9E%D0%BD%D0%B8%D0%B4%D0%B7%D1%83%D0%BA%D0%B0'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/GTO_(%E6%BC%AB%E7%94%BB)'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=147'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=311'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/245-great-teacher-onizuka'));
    }

    /**
     * Get Beck item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemBeck(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/beck.jpg')
            ->setDateEnd(new \DateTime('2005-03-31'))
            ->setDatePremiere(new \DateTime('2004-10-07'))
            ->setDuration(25)
            ->setEpisodes(
'1. The View at Fourteen (07.10.2004, 25 мин.)
2. Live House (14.10.2004, 25 мин.)
3. Moon on the Water (21.10.2004, 25 мин.)
4. Strum the Guitar (28.10.2004, 25 мин.)
5. Beck (04.11.2004, 25 мин.)
6. Hyodo and the Jaguar (11.11.2004, 25 мин.)
7. Prudence (18.11.2004, 25 мин.)
8. Broadcast in the School (25.11.2004, 25 мин.)
9. The Night Before Live (02.12.2004, 25 мин.)
10. Face (09.12.2004, 25 мин.)
11. Summer Holiday (16.12.2004, 25 мин.)
12. Secret Live (23.12.2004, 25 мин.)
13. Ciel Bleu (30.12.2004, 25 мин.)
14. Dream (06.01.2005, 25 мин.)
15. Back to School (13.01.2005, 25 мин.)
16. Indies (20.01.2005, 25 мин.)
17. Three Days (27.01.2005, 25 мин.)
18. Leon Sykes (03.02.2005, 25 мин.)
19. Blues (10.02.2005, 25 мин.)
20. Greatful Sound (17.02.2005, 25 мин.)
21. Write Music (24.02.2005, 25 мин.)
22. Night Before the Festival (03.03.2005, 25 мин.)
23. Festival (10.03.2005, 25 мин.)
24. Third Stage (17.03.2005, 25 мин.)
25. Slip Out (24.03.2005, 25 мин.)
26. America (31.03.2005, 25 мин.)'
            )
            ->setEpisodesNumber('26')
            ->setName('Beck: Mongolian Chop Squad')
            ->setPath($storage->getPath().'Beck (2004) [TV]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'В начале была Песня – так верят многие народы, и не зря музыка все так же объединяет нас спустя '
                .'тысячелетия после начала писаной и неписаной истории. Бек – это аниме про молодых людей, ищущих '
                .'свой жизненный путь, и про уже состоявшихся людей, которым музыка помогла и помогает в жизни. Бек – '
                .'это аниме про универсальный язык, на котором могут разговаривать разные поколения. А еще это аниме '
                .'про современное общество, в котором всплески таланта и искренние порывы души рано или поздно '
                .'становятся частью глобальной индустрии развлечений. Можно спорить – хорошо это или плохо, но таков '
                .'мир, в котором мы живем.'.PHP_EOL
                .'А вообще-то, Бек – это рассказ о простом японском парне, 14-летнем Юкио Танаке, который волею '
                .'судьбы встретился с молодым гитаристом Рюскэ Минами и, благодаря таланту, силе духа, простому и '
                .'открытому характеру, нашел свое место в жизни, обрел друзей и встретил любовь. Это рассказ о поиске '
                .'путей самовыражения, на которых искренность и честность приносят радость, а злоба и лицемерие '
                .'заводят в тупик. А еще это рассказ о встрече непростых людей, которые сумели создать и сохранить '
                .'рок-группу, то самое целое, которое куда больше суммы слагаемых. Именно так и рождается настоящая '
                .'музыка. Именно так вышло одно из лучших музыкальных аниме всех времен!'
            )
            ->setStudio($this->getStudio('Madhouse'))
            ->setType($this->getType('tv'))
            ->addGenre($this->getGenre('Comedy'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('Musical'))
            ->addGenre($this->getGenre('Romance'))
            ->addName((new Name())->setName('Бек'))
            ->addName((new Name())->setName('Бек: Восточная Ударная Группа'))
            ->addName((new Name())->setName('Beck'))
            ->addName((new Name())->setName('Beck - Mongorian Chop Squad'))
            ->addName((new Name())->setName('Beck Mongolian Chop Squad'))
            ->addName((new Name())->setName('BECK　ベック'))
            ->addName((new Name())->setName('ベック'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=4404'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=2320'))
            ->addSource((new Source())->setUrl('http://myanimelist.net/anime/57/'))
            ->addSource((new Source())->setUrl('http://cal.syoboi.jp/tid/490/time'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=321252'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/BECK:_Mongolian_Chop_Squad'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/BECK:_Mongolian_Chop_Squad'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/BECK_%28%E6%BC%AB%E7%94%BB%29'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=725'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=2671'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/57-beck'));
    }

    /**
     * Get Samurai X: Trust & Betrayal item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemSamuraiXTrustAndBetrayal(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/samurai-x-trust-betrayal.jpg')
            ->setDateEnd(new \DateTime('1999-09-22'))
            ->setDatePremiere(new \DateTime('1999-02-20'))
            ->setDuration(30)
            ->setEpisodes(
'1. The Man of the Slashing Sword (20.02.1999, 30 мин.)
2. The Lost Cat (21.04.1999, 30 мин.)
3. The Previous Night at the Mountain Home (19.06.1999, 30 мин.)
4. The Cross-Shaped Wound (22.09.1999, 30 мин.)'
            )
            ->setEpisodesNumber('4')
            ->setName('Samurai X: Trust & Betrayal')
            ->setPath($storage->getPath().'Samurai X - Trust Betrayal (1999) [OVA]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'XIX век, Японию раздирает клановая вражда. Маленький Синта в детстве был продан работорговцам и '
                .'попал вместе с другими в засаду - всех спутников мальчика на его глазах закололи, его же спас '
                .'случайно проходивший мимо воин, мастерски владеющий мечом. Синта поступает к нему в ученики и '
                .'становится мастером меча по имени Кэнсин. Парень выбирает жизненный путь убийцы экстра-класса. В '
                .'одной из операций он встречает таинственную девушку Томоэ, которая видит Кэнсина в действии. '
                .'Привыкший не оставлять свидетелей, самурай не убивает девушку, а забирает её с собой. Что-то '
                .'дрогнуло у него в душе при виде Томоэ, возможно, она смягчит этого смелого, но холодного человека?'
            )
            ->setStudio($this->getStudio('Studio DEEN'))
            ->setType($this->getType('ova'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('Romance'))
            ->addGenre($this->getGenre('Samurai'))
            ->addName((new Name())->setName('Бродяга Кэнсин: Воспоминания'))
            ->addName((new Name())->setName('Samurai X: Trust and Betrayal'))
            ->addName((new Name())->setName('Rurouni Kenshin: Meiji Kenkaku Romantan - Tsuioku Hen'))
            ->addName((new Name())->setName('Rurouni Kenshin: Meiji Kenkaku Romantan - Tsuiokuhen'))
            ->addName((new Name())->setName('Rurouni Kenshin: Tsuioku Hen'))
            ->addName((new Name())->setName('るろうに剣心 -明治剣客浪漫譚-　追憶編'))
            ->addName((new Name())->setName('るろうに剣心―明治剣客浪漫譚―追憶編'))
            ->addName((new Name())->setName('るろうに剣心 -明治剣客浪漫譚- 追憶編'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=210'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=73'))
            ->addSource((new Source())->setUrl('http://myanimelist.net/anime/44/'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=88146'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Rurouni_Kenshin'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/%D0%A1%D0%B0%D0%BC%D1%83%D1%80%D0%B0%D0%B9_X'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/%E3%82%8B%E3%82%8D%E3%81%86%E3%81%AB%E5%89%A3%E5%BF%83_-%E6%98%8E%E6%B2%BB%E5%89%A3%E5%AE%A2%E6%B5%AA%E6%BC%AB%E8%AD%9A-'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=870'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=82'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/44-rurouni-kenshin-meiji-kenkaku-romantan-tsuiokuhen'));
    }

    /**
     * Get My Neighbor Totoro item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemMyNeighborTotoro(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/tonari-no-totoro.jpg')
            ->setDatePremiere(new \DateTime('1988-04-16'))
            ->setDuration(88)
            ->setEpisodesNumber('1')
            ->setName('My Neighbor Totoro')
            ->setPath($storage->getPath().'Tonari no Totoro (1988) [TV]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Япония, пятидесятые годы прошлого века. Переехав в деревню, две маленькие сестры Сацуки (старшая) и '
                .'Мэй (младшая) глубоко внутри дерева обнаружили необыкновенный, чудесный мир, населённый Тоторо, '
                .'очаровательными пушистыми созданиями, с которыми у девочек сразу же завязалась дружба. Одни из них '
                .'большие, другие совсем крохотные, но у всех у них огромное, доброе сердце и магические способности '
                .'совершать необыкновенные вещи, наподобие полётов над горами или взращивания огромного дерева за '
                .'одну ночь! Но увидеть этих существ могут лишь дети, которые им приглянутся... Подружившись с '
                .'сёстрами, Тоторо не только устраивают им воздушную экскурсию по своим владениям, но и помогают Мэй '
                .'повидаться с лежащей в больнице мамой.'
            )
            ->setStudio($this->getStudio('Studio Ghibli'))
            ->setType($this->getType('feature'))
            ->addGenre($this->getGenre('Adventure'))
            ->addGenre($this->getGenre('Comedy'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('Fable'))
            ->addName((new Name())->setName('Мой сосед Тоторо'))
            ->addName((new Name())->setName('Наш сосед Тоторо'))
            ->addName((new Name())->setName('Tonari no Totoro'))
            ->addName((new Name())->setName('My Neighbour Totoro'))
            ->addName((new Name())->setName('となりのトトロ'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=534'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=303'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=150435'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/My_Neighbor_Totoro'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/%D0%9D%D0%B0%D1%88_%D1%81%D0%BE%D1%81%D0%B5%D0%B4_%D0%A2%D0%BE%D1%82%D0%BE%D1%80%D0%BE'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/%E3%81%A8%E3%81%AA%E3%82%8A%E3%81%AE%E3%83%88%E3%83%88%E3%83%AD'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=266'))
            ->addSource((new Source())->setUrl('http://uanime.org.ua/anime/145.html'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=62'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/523-tonari-no-totoro'));
    }

    /**
     * Get Hellsing item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemHellsing(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/hellsing.jpg')
            ->setDateEnd(new \DateTime('2012-12-26'))
            ->setDatePremiere(new \DateTime('2006-02-10'))
            ->setDuration(50)
            ->setEpisodes(
'1. Hellsing I (10.02.2006, 50 мин.)
2. Hellsing II (25.08.2006, 45 мин.)
3. Hellsing III (04.04.2007, 50 мин.)
4. Hellsing IV (22.02.2008, 55 мин.)
5. Hellsing V (21.11.2008, 40 мин.)
6. Hellsing VI (24.07.2009, 40 мин.)
7. Hellsing VII (23.12.2009, 45 мин.)
8. Hellsing VIII (27.07.2011, 50 мин.)
9. Hellsing IX (15.02.2012, 45 мин.)
10. Hellsing X (26.12.2012, 65 мин.)'
            )
            ->setEpisodesNumber('10')
            ->setFileInfo('+ 4 спэшла')
            ->setName('Hellsing')
            ->setPath($storage->getPath().'Hellsing (2006) [OVA]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'На каждое действие найдётся противодействие – для борьбы с кровожадной нечистью в Великобритании был '
                .'создан Королевский Орден Протестантских Рыцарей, которому служит древнейший вампир Алукард. '
                .'Согласно заключённому договору, он подчиняется главе тайной организации «Хеллсинг».'.PHP_EOL
                .'У Ватикана свой козырь – особый Тринадцатый Отдел, организация «Искариот», в составе которой '
                .'неубиваемый отец Александр. Для них Алукард ничем не отличается от остальных монстров.'.PHP_EOL
                .'Однако всем им придётся на время забыть о дрязгах между католической и англиканской церквями, когда '
                .'на сцену выйдет могущественный враг из прошлого – загадочный Майор во главе секретной нацистской '
                .'организации «Миллениум».'.PHP_EOL
                .'Но пока не началась битва за Англию, Алукард занят воспитанием новообращённой вампирши: Виктория '
                .'Серас раньше служила в полиции, а теперь ей приходится привыкать к жизни в старинном особняке, к '
                .'своим новым способностям и новым обязанностям. Даже хозяйка Алукарда, леди Интегра, не знает, зачем '
                .'он обратил эту упрямую девушку...'.PHP_EOL
                .'Вторая экранизация манги Хирано Кота дотошно следует оригиналу, и потому заметно отличается от '
                .'сериала, ведь именно чёрный юмор, реки крови, харизматичные враги и закрученный конфликт сделали '
                .'«Хеллсинга» всемирно популярным.'
            )
            ->setStudio($this->getStudio('Satelight'))
            ->setType($this->getType('ova'))
            ->addGenre($this->getGenre('Adventure'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('Mysticism'))
            ->addName((new Name())->setName('Хеллсинг'))
            ->addName((new Name())->setName('Hellsing Ultimate'))
            ->addName((new Name())->setName('Hellsing OVA'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=5114'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=3296'))
            ->addSource((new Source())->setUrl('http://myanimelist.net/anime/777/'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=323337'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Hellsing_%28manga%29'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/%D0%A5%D0%B5%D0%BB%D0%BB%D1%81%D0%B8%D0%BD%D0%B3:_%D0%92%D0%BE%D0%B9%D0%BD%D0%B0_%D1%81_%D0%BD%D0%B5%D1%87%D0%B8%D1%81%D1%82%D1%8C%D1%8E'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/HELLSING'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=988'))
            ->addSource((new Source())->setUrl('http://uanime.org.ua/anime/63.html'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=4340'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/270-hellsing'));
    }

    /**
     * Get Gintama item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemGintama(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/gintama.jpg')
            ->setDateEnd(new \DateTime('2010-03-25'))
            ->setDatePremiere(new \DateTime('2006-04-04'))
            ->setDuration(25)
            ->setEpisodesNumber('201')
            ->setName('Gintama')
            ->setPath($storage->getPath().'Gintama (2006) [TV-1]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Абсурдистская фантастическо-самурайская пародийная комедия о приключениях фрилансеров в '
                .'псевдо-средневековом Эдо. Захватив Землю, пришельцы Аманто запретили ношение мечей, единственный, в '
                .'ком ещё жив подлинно японский дух – самоуверенный сластёна Гинтоки Саката. Неуклюжий очкарик '
                .'Симпати нанялся к нему в ученики. Третьим в их команде стала прелестная Кагура из сильнейшей во '
                .'вселенной семьи Ятудзоку, а с ней её питомец Садахару – пёсик размером с бегемота, обладающий '
                .'забавной привычкой грызть головы всем, кто под морду подвернётся. Они называют себя «мастерами на '
                .'все руки» и выполняют любые заказы – главное, чтобы заплатили.'.PHP_EOL
                .'Кроме инопланетян с ушками, бандитов со шрамами, самураев с бокэнами, девушек-ниндзя с натто и '
                .'странных существ, в «Гинтаме» встречаются также Синсэнгуми, состоящие из придурковатых юношей в '
                .'европейской одежде. Высмеиванию подвергается множество штампов, пародируется «Блич», «Ковбой Бибоп» '
                .'и многие другие известные сериалы. Юмор колеблется от «сортирного» до «тонкой иронии», в целом это '
                .'весьма «зубастая» комедия, лишённая каких-либо рамок и ограничений.'
            )
            ->setStudio($this->getStudio('Sunrise'))
            ->setType($this->getType('tv'))
            ->addGenre($this->getGenre('Adventure'))
            ->addGenre($this->getGenre('Comedy'))
            ->addGenre($this->getGenre('Fantastic'))
            ->addName((new Name())->setName('Гинтама'))
            ->addName((new Name())->setName('Silver Soul'))
            ->addName((new Name())->setName('The Best of Gintama-san'))
            ->addName((new Name())->setName('Yorinuki Gintama-san'))
            ->addName((new Name())->setName('銀魂[ぎんたま]'))
            ->addName((new Name())->setName('よりぬき銀魂さん'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=6236'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=3468'))
            ->addSource((new Source())->setUrl('http://myanimelist.net/anime/918/'))
            ->addSource((new Source())->setUrl('http://cal.syoboi.jp/tid/853/time'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=324863'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Gintama'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/Gintama'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/%E9%8A%80%E9%AD%82_%28%E3%82%A2%E3%83%8B%E3%83%A1%29'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=2022'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=5013'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/918-gintama'));
    }

    /**
     * Get Bakuman item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemBakuman(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/bakuman.jpg')
            ->setDateEnd(new \DateTime('2011-04-02'))
            ->setDatePremiere(new \DateTime('2010-10-02'))
            ->setDuration(25)
            ->setEpisodes(
'1. Dream and Reality (02.10.2010, 25 мин.)
2. Stupid and Clever (09.10.2010, 25 мин.)
3. Parent and Child (16.10.2010, 25 мин.)
4. Time and Key (23.10.2010, 25 мин.)
5. Summer and Storyboard (30.10.2010, 25 мин.)
6. Carrot and Stick (06.11.2010, 25 мин.)
7. Tears and Tears (13.11.2010, 25 мин.)
8. Anxiety and Anticipation (20.11.2010, 25 мин.)
9. Regret and Consent (27.11.2010, 25 мин.)
10. 10 and 2 (04.12.2010, 25 мин.)
11. Chocolate and Next! (11.12.2010, 25 мин.)
12. Feast and Graduation (18.12.2010, 25 мин.)
13. Early Results And The Real Deal (25.12.2010, 25 мин.)
14. Battles and Copying (08.01.2011, 25 мин.)
15. Debut and Hectic (15.01.2011, 25 мин.)
16. Wall and Kiss (22.01.2011, 25 мин.)
17. Braggart and Kindness (29.01.2011, 25 мин.)
18. Jealousy and Love (05.02.2011, 25 мин.)
19. Two and One (12.02.2011, 25 мин.)
20. Cooperation and Conditions (19.02.2011, 25 мин.)
21. Literature and Music (26.02.2011, 25 мин.)
22. Solidarity and Breakdown (05.03.2011, 25 мин.)
23. Tuesday and Friday (19.03.2011, 25 мин.)
24. Call and Eve (26.03.2011, 25 мин.)
25. Yes and No (02.04.2011, 25 мин.)'
            )
            ->setEpisodesNumber('25')
            ->setName('Bakuman')
            ->setPath($storage->getPath().'Bakuman (2010) [TV-1]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Хорошие школьные оценки – престижный вуз – крупная корпорация: вот жизненный план большинства '
                .'японских юношей и девушек. Но в каждом поколении находятся упрямцы, готовые отринуть синицу в руках '
                .'ради возможности сохранить индивидуальность и заняться любимым делом. Таковы юный художник Моритака '
                .'Масиро и начинающий писатель Акито Такаги, которые пока оканчивают среднюю школу, но уже приняли '
                .'непростое решение – посвятить жизнь созданию манги, уникального феномена японской культуры.'.PHP_EOL
                .'Герои сериала - фанаты манги, лауреаты юношеских конкурсов и знакомы с реалиями «взрослого» '
                .'шоу-бизнеса, где наверх пробиваются единицы. Но когда еще рисковать, как не в 16 лет?! А тут '
                .'Моритака, склонный к рефлексии, внезапно узнает, что его любимая и одноклассница, Михо Адзуки, '
                .'хочет быть актрисой-сэйю, то есть работать по «смежной специальности». Будучи во власти эйфории, '
                .'парень тут же предлагает девушке две вещи: сыграть когда-нибудь в аниме по их манге и… выйти за '
                .'него замуж. Самое интересное, что Адзуки соглашается на то и другое – но в этой же строгой '
                .'последовательности. Теперь творческому дуэту придется поставить на карту все – тяжкий труд, талант, '
                .'потенциальную карьеру – и крепко верить в себя и свою удачу. Не попробуешь – не узнаешь, Драгонболл '
                .'тоже не сразу строился!'
            )
            ->setStudio($this->getStudio('J.C.Staff'))
            ->setType($this->getType('tv'))
            ->addGenre($this->getGenre('Comedy'))
            ->addGenre($this->getGenre('Everyday'))
            ->addName((new Name())->setName('Бакуман'))
            ->addName((new Name())->setName('バクマン。'))
            ->addName((new Name())->setName('バクマン.'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=11197'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=7251'))
            ->addSource((new Source())->setUrl('http://myanimelist.net/anime/7674/'))
            ->addSource((new Source())->setUrl('http://cal.syoboi.jp/tid/2037/time'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=335759'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Bakuman'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/Bakuman'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/%E3%83%90%E3%82%AF%E3%83%9E%E3%83%B3%E3%80%82_%28%E3%82%A2%E3%83%8B%E3%83%A1%29'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=3109'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=7740'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/7674-bakuman'));
    }

    /**
     * Get Tengen Toppa Gurren Lagann item
     *
     * @param \AnimeDb\Bundle\CatalogBundle\Entity\Storage $storage
     *
     * @return \AnimeDb\Bundle\CatalogBundle\Entity\Item
     */
    protected function getItemTengenToppaGurrenLagann(Storage $storage)
    {
        return (new Item())
            ->setCountry($this->getCountry('JP'))
            ->setCover('example/tengen-toppa-gurren-lagann.jpg')
            ->setDateEnd(new \DateTime('2007-09-30'))
            ->setDatePremiere(new \DateTime('2007-04-01'))
            ->setDuration(25)
            ->setEpisodes(
'1. Pierce the Heavens with Your Drill! (01.04.2007, 25 мин.)
2. I Said I`d Ride It (08.04.2007, 25 мин.)
3. You Two-Faced Son of a Bitch! (15.04.2007, 25 мин.)
4. Does Having So Many Faces Make You Great? (22.04.2007, 25 мин.)
5. I Don`t Understand It At All! (29.04.2007, 25 мин.)
6. All of You Bastards Put Us In Hot Water! (06.05.2007, 25 мин.)
7. You`ll Be the One To Do That! (13.05.2007, 25 мин.)
8. Farewell Comrades! (20.05.2007, 25 мин.)
9. Just What Exactly Is a Human? (27.05.2007, 25 мин.)
10. Who Really Was Your Big Brother? (03.06.2007, 25 мин.)
11. Simon, Please Remove Your Hand (10.06.2007, 25 мин.)
12. Youko-san, I Have Something to Ask of You (17.06.2007, 25 мин.)
13. Everybody, Eat to Your Heart`s Content (24.06.2007, 25 мин.)
14. How Are You, Everyone? (01.07.2007, 25 мин.)
15. I`ll Head Towards Tomorrow (08.07.2007, 25 мин.)
16. Summary Episode (15.07.2007, 25 мин.)
17. You Understand Nothing (22.07.2007, 25 мин.)
18. I`ll Make You Tell the Truth of the World (29.07.2007, 25 мин.)
19. We Must Survive. No Matter What it Takes! (05.08.2007, 25 мин.)
20. Oh God, To How Far Will You Test Us? (12.08.2007, 25 мин.)
21. You Must Survive (19.08.2007, 25 мин.)
22. And to Space (26.08.2007, 25 мин.)
23. Let`s Go, The Final Battle (02.09.2007, 25 мин.)
24. We Will Never Forget, This Minute and Second (09.09.2007, 25 мин.)
25. I Accept Your Dying Wish! (16.09.2007, 25 мин.)
26. Let`s Go, Comrades! (23.09.2007, 25 мин.)
27. All the Lights in the Sky are Stars (30.09.2007, 25 мин.)'
            )
            ->setEpisodesNumber('27')
            ->setFileInfo('+ 2 спэшла')
            ->setName('Tengen Toppa Gurren Lagann')
            ->setPath($storage->getPath().'Tengen Toppa Gurren Lagann (2007) [TV]'.DIRECTORY_SEPARATOR)
            ->setStorage($storage)
            ->setSummary(
                'Сотни лет люди живут в глубоких пещерах, в постоянном страхе перед землетрясениями и обвалами. В '
                .'одной из таких подземных деревень живет мальчик Симон и его «духовный наставник» — молодой парень '
                .'Камина. Камина верит, что наверху есть другой мир, без стен и потолков; его мечта — попасть туда. '
                .'Но его мечты остаются пустыми фантазиями, пока в один прекрасный день Симон случайно не находит '
                .'дрель... вернее, ключ от странного железного лица в толще земли. В этот же день происходит '
                .'землетрясение, и потолок пещеры рушится — так начинается поистине эпическое приключение Симона, '
                .'Камины и их компаньонов в новом для них мире: мире под открытым небом огромной Вселенной.'
            )
            ->setStudio($this->getStudio('Gainax'))
            ->setType($this->getType('tv'))
            ->addGenre($this->getGenre('Adventure'))
            ->addGenre($this->getGenre('Fantastic'))
            ->addGenre($this->getGenre('Drama'))
            ->addGenre($this->getGenre('Mecha'))
            ->addName((new Name())->setName('Гуррен-Лаганн'))
            ->addName((new Name())->setName('Heavenly Breakthrough Gurren Lagann'))
            ->addName((new Name())->setName('Tengen Toppa Gurren-Lagann'))
            ->addName((new Name())->setName('天元突破 グレンラガン'))
            ->addName((new Name())->setName('天元突破グレンラガン'))
            ->addName((new Name())->setName('Tengen Toppa Gurren Lagann: Ore no Gurren wa Pikka Pika!!'))
            ->addName((new Name())->setName('天元突破 グレンラガン 俺のグレンはピッカピカ!!'))
            ->addSource((new Source())->setUrl('http://www.animenewsnetwork.com/encyclopedia/anime.php?id=6698'))
            ->addSource((new Source())->setUrl('http://anidb.net/perl-bin/animedb.pl?show=anime&aid=4575'))
            ->addSource((new Source())->setUrl('http://cal.syoboi.jp/tid/1000/time'))
            ->addSource((new Source())->setUrl('http://www.allcinema.net/prog/show_c.php?num_c=326669'))
            ->addSource((new Source())->setUrl('http://en.wikipedia.org/wiki/Tengen_Toppa_Gurren_Lagann'))
            ->addSource((new Source())->setUrl('http://ru.wikipedia.org/wiki/Tengen_Toppa_Gurren_Lagann'))
            ->addSource((new Source())->setUrl('http://ja.wikipedia.org/wiki/%E5%A4%A9%E5%85%83%E7%AA%81%E7%A0%B4%E3%82%B0%E3%83%AC%E3%83%B3%E3%83%A9%E3%82%AC%E3%83%B3'))
            ->addSource((new Source())->setUrl('http://www.fansubs.ru/base.php?id=1769'))
            ->addSource((new Source())->setUrl('http://www.world-art.ru/animation/animation.php?id=5959'))
            ->addSource((new Source())->setUrl('http://shikimori.org/animes/2001-tengen-toppa-gurren-lagann'));
    }
}
