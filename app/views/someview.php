<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */
?>

<!--html>(head>title)+body>(header>h1+h2+nav>ul>li*3>a)+(article>section*2)+footer>p-->

<html>
<head><title></title></head>
<body>
<header>
    <h1><?php echo $this->title ?></h1>
    <h2><?php echo $this->subtitle ?></h2>
    <nav>
        <ul>
            <?php foreach($this->navigation as $item): ?>
                <li><a href="<?php echo $item->link ?>"><?php echo $item->name ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>
<article>
    <section>
        <p>ashfahsfjkhasf</p>
    </section>
    <section>
        <p>
            asödjföasjöfjasöjdföjas
        </p>
    </section>
</article>
<footer>
    <p><?php $this->copyright ?></p>
</footer>
</body>
</html>