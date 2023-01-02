<x-app-layout>
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <body>
            <x-show-posts pageTitle="同じアイテムを使用した投稿" :posts="$posts" />
            <p>
                <?php
                    $dom = new DOMDocument('1.0', 'UTF-8');
                    $html = file_get_contents( $item->URL );//データを抽出したいURLを入力
                    @$dom->loadHTML($html);
                    $xpath = new DOMXpath($dom);
                    echo $xpath->query("//title")->item(0)->nodeValue; //タイトルを抽出して出力
                ?>
            </p>
        </body>
    </html>
</x-app-layout>