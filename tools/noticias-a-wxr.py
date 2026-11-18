"""Genera un archivo WXR (Herramientas → Importar → WordPress) con las noticias de amuma.org,
convertidas de Divi a bloques normales de WordPress, con sus imágenes como adjuntos."""
import json, re, html, sys, os
from datetime import datetime, timezone
from xml.sax.saxutils import escape
from bs4 import BeautifulSoup, NavigableString, Comment

D = sys.argv[1]           # carpeta con posts*.json y media*.json
OUT = sys.argv[2]         # archivo .xml de salida

posts = json.load(open(os.path.join(D, 'posts1.json'))) + json.load(open(os.path.join(D, 'posts2.json')))
media = []
for i in range(1, 7):
    f = os.path.join(D, 'media%d.json' % i)
    if os.path.exists(f):
        media += json.load(open(f))

by_id = {m['id']: m for m in media}
url_to_id = {}
for m in media:
    url_to_id[m['source_url']] = m['id']
    for s in (m.get('media_details') or {}).get('sizes', {}).values():
        if s.get('source_url'):
            url_to_id[s['source_url']] = m['id']

def norm_url(u):
    if not u:
        return u
    u = html.unescape(u.split('?')[0])
    return u.replace('http://amuma.org', 'https://amuma.org').replace('://www.amuma.org', '://amuma.org')

def lookup(u):
    u = norm_url(u)
    if u in url_to_id:
        return url_to_id[u]
    base = re.sub(r'-\d+x\d+(\.\w+(?:\.webp)?)$', r'\1', u)
    return url_to_id.get(base)

def full_url(mid, fallback):
    return by_id[mid]['source_url'] if mid in by_id else norm_url(fallback)

used_media = {}   # id -> parent post id

INLINE = {'a', 'strong', 'b', 'em', 'i', 'u', 'span', 'font', 'br', 'sup', 'sub', 'small', 'mark', 'code', 's', 'del', 'ins', 'abbr', 'time', 'label'}
HEAD = {'h1': 2, 'h2': 2, 'h3': 3, 'h4': 4, 'h5': 5, 'h6': 6}

def is_emoji_img(tag):
    src = tag.get('src', '') or ''
    return bool(re.search(r'fbcdn|emoji|s\.w\.org', src))

def inline_html(node):
    """HTML en línea limpio: solo enlaces y negritas/cursivas."""
    out = []
    for c in node.children if hasattr(node, 'children') else []:
        if isinstance(c, Comment):
            continue
        if isinstance(c, NavigableString):
            out.append(escape(str(c)))
        elif c.name == 'img' and is_emoji_img(c):
            out.append(escape(c.get('alt', '')))
        elif c.name == 'br':
            out.append('<br>')
        elif c.name == 'a':
            href = c.get('href', '')
            inner = inline_html(c)
            if href and not href.startswith('javascript'):
                out.append('<a href="%s">%s</a>' % (escape(href, {'"': '&quot;'}), inner))
            else:
                out.append(inner)
        elif c.name in ('strong', 'b'):
            inner = inline_html(c)
            out.append('<strong>%s</strong>' % inner if inner.strip() else inner)
        elif c.name in ('em', 'i'):
            inner = inline_html(c)
            out.append('<em>%s</em>' % inner if inner.strip() else inner)
        elif c.name in INLINE:
            out.append(inline_html(c))
        else:
            out.append(inline_html(c))
    return ''.join(out)

def tidy(s):
    s = re.sub(r'(\s*<br>\s*)+$', '', s)
    s = re.sub(r'^(\s*<br>\s*)+', '', s)
    s = s.replace('\xa0', ' ')
    s = re.sub(r'[ \t\r\n]+', ' ', s).strip()
    return s

# ---- Generadores de bloques ----
def b_par(h):
    return '<!-- wp:paragraph -->\n<p>%s</p>\n<!-- /wp:paragraph -->' % h

def b_head(h, level):
    level = max(2, min(level, 4))
    attrs = '' if level == 2 else ' {"level":%d}' % level
    return '<!-- wp:heading%s -->\n<h%d class="wp-block-heading">%s</h%d>\n<!-- /wp:heading -->' % (attrs, level, h, level)

def b_list(items, ordered):
    tag = 'ol' if ordered else 'ul'
    inner = ''.join('<!-- wp:list-item -->\n<li>%s</li>\n<!-- /wp:list-item -->\n' % i for i in items)
    attrs = ' {"ordered":true}' if ordered else ''
    return '<!-- wp:list%s -->\n<%s class="wp-block-list">%s</%s>\n<!-- /wp:list -->' % (attrs, tag, inner, tag)

def b_image(url, mid, alt=''):
    url = full_url(mid, url) if mid else norm_url(url)
    if mid:
        return '<!-- wp:image {"id":%d,"sizeSlug":"large","linkDestination":"none"} -->\n<figure class="wp-block-image size-large"><img src="%s" alt="%s" class="wp-image-%d"/></figure>\n<!-- /wp:image -->' % (mid, escape(url), escape(alt, {'"': '&quot;'}), mid)
    return '<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->\n<figure class="wp-block-image size-large"><img src="%s" alt="%s"/></figure>\n<!-- /wp:image -->' % (escape(url), escape(alt, {'"': '&quot;'}))

def b_gallery(imgs):
    inner = ''.join(b_image(u, m) + '\n' for u, m in imgs)
    return '<!-- wp:gallery {"linkTo":"none"} -->\n<figure class="wp-block-gallery has-nested-images columns-default is-cropped">%s</figure>\n<!-- /wp:gallery -->' % inner

def b_video(url):
    if re.search(r'youtu\.?be|vimeo|facebook\.com', url or ''):
        prov = 'youtube' if 'youtu' in url else ('vimeo' if 'vimeo' in url else 'facebook')
        url = re.sub(r'youtube\.com/embed/([\w-]+).*', r'youtube.com/watch?v=\1', url)
        return '<!-- wp:embed {"url":"%s","type":"video","providerNameSlug":"%s"} -->\n<figure class="wp-block-embed is-type-video is-provider-%s"><div class="wp-block-embed__wrapper">\n%s\n</div></figure>\n<!-- /wp:embed -->' % (url, prov, prov, url)
    return '<!-- wp:video -->\n<figure class="wp-block-video"><video controls src="%s"></video></figure>\n<!-- /wp:video -->' % escape(norm_url(url))

def b_html(h):
    return '<!-- wp:html -->\n%s\n<!-- /wp:html -->' % h

class Builder:
    def __init__(self, post_id):
        self.out, self.buf, self.post_id = [], [], post_id

    def use(self, url):
        mid = lookup(url)
        if mid and mid not in used_media:
            used_media[mid] = self.post_id
        return mid

    def flush(self):
        h = tidy(''.join(self.buf))
        self.buf = []
        if h and re.sub(r'<[^>]+>|\s', '', h):
            for part in re.split(r'(?:\s*<br>\s*){2,}', h):
                part = tidy(part)
                if part and re.sub(r'<[^>]+>|\s', '', part):
                    self.out.append(b_par(part))

    def walk(self, node):
        for c in list(node.children):
            if isinstance(c, Comment):
                continue
            if isinstance(c, NavigableString):
                self.buf.append(escape(str(c)))
                continue
            name = c.name
            if name == 'img':
                if is_emoji_img(c):
                    self.buf.append(escape(c.get('alt', '')))
                else:
                    self.flush()
                    src = c.get('src') or c.get('data-src')
                    a = c.find_parent('a')
                    if a and re.search(r'\.(jpe?g|png|webp)', a.get('href', ''), re.I):
                        src = a['href']
                    self.out.append(b_image(src, self.use(src), c.get('alt', '')))
            elif name in HEAD:
                self.flush()
                h = tidy(inline_html(c))
                if h:
                    self.out.append(b_head(h, HEAD[name]))
            elif name in ('ul', 'ol'):
                self.flush()
                items = [tidy(inline_html(li)) for li in c.find_all('li', recursive=False)]
                items = [i for i in items if i]
                if items:
                    self.out.append(b_list(items, name == 'ol'))
            elif name == 'table':
                self.flush()
                for t in c.find_all(True):
                    for a in list(t.attrs):
                        if a not in ('href', 'colspan', 'rowspan'):
                            del t[a]
                self.out.append('<!-- wp:table -->\n<figure class="wp-block-table">%s</figure>\n<!-- /wp:table -->' % str(c))
            elif name in ('iframe', 'video'):
                self.flush()
                src = c.get('src') or (c.find('source').get('src') if c.find('source') else '')
                if src:
                    self.out.append(b_video(src))
            elif name in ('script', 'style', 'noscript', 'form', 'input', 'button'):
                continue
            elif name in ('p', 'div', 'section', 'article', 'blockquote', 'figure', 'center', 'header', 'footer', 'main'):
                self.flush()
                self.walk(c)
                self.flush()
            elif name in INLINE:
                if c.find('img') and any(not is_emoji_img(i) for i in c.find_all('img')):
                    self.walk(c)
                else:
                    self.buf.append(inline_html(BeautifulSoup('<x>%s</x>' % str(c), 'html.parser').x))
            else:
                self.walk(c)

    def module(self, mod):
        cls = mod.get('class', [])
        if 'et_pb_gallery' in cls:
            self.flush()
            imgs = []
            for it in mod.select('.et_pb_gallery_image'):
                a = it.find('a'); img = it.find('img')
                src = (a.get('href') if a else None) or (img.get('src') if img else None)
                # Iconos de la plantilla de Divi que se colaron en algunas galerías: fuera.
                if src and 'women-health-center-' not in src:
                    imgs.append((src, self.use(src)))
            if imgs:
                self.out.append(b_gallery(imgs))
        elif 'et_pb_image' in cls:
            img = mod.find('img')
            if img:
                self.flush()
                src = img.get('src')
                self.out.append(b_image(src, self.use(src), img.get('alt', '')))
        elif 'et_pb_video' in cls:
            self.flush()
            src = mod.find('source'); ifr = mod.find('iframe')
            url = (src.get('src') if src else None) or (ifr.get('src') if ifr else '')
            if url:
                self.out.append(b_video(url))
        elif 'et_pb_blurb' in cls:
            self.flush()
            img = mod.find('img'); t = mod.select_one('.et_pb_module_header'); d = mod.select_one('.et_pb_blurb_description')
            if img:
                src = img.get('src'); self.out.append(b_image(src, self.use(src)))
            if t and t.get_text(strip=True):
                self.out.append(b_head(escape(t.get_text(strip=True)), 3))
            if d:
                self.walk(d); self.flush()
        elif 'et_pb_text' in cls:
            inner = mod.select_one('.et_pb_text_inner') or mod
            self.walk(inner); self.flush()
        else:
            self.walk(mod); self.flush()

def untex(s):
    return re.sub(r'[»«″”“]|&#8243;|&#8221;|&#8220;', '"', s)

def convert(p):
    b = Builder(p['id'])
    rendered = p['content']['rendered']
    if '[et_pb_' in rendered:
        # Shortcodes sin procesar: se trocea el texto por los shortcodes.
        txt = html.unescape(rendered)
        parts = re.split(r'(\[/?et_pb_[^\]]*\])', txt)
        for part in parts:
            m = re.match(r'\[et_pb_(gallery|image)([^\]]*)\]', untex(part))
            if m:
                b.flush()
                attrs = dict(re.findall(r'(\w+)="([^"]*)"', untex(m.group(2))))
                if m.group(1) == 'gallery':
                    imgs = []
                    for x in attrs.get('gallery_ids', '').split(','):
                        if x.strip().isdigit() and int(x) in by_id:
                            mid = int(x); used_media.setdefault(mid, p['id'])
                            imgs.append((by_id[mid]['source_url'], mid))
                    if imgs:
                        b.out.append(b_gallery(imgs))
                elif attrs.get('src'):
                    b.out.append(b_image(attrs['src'], b.use(attrs['src'])))
            elif part.startswith('[') and 'et_pb_' in part:
                continue
            else:
                b.walk(BeautifulSoup(part, 'html.parser')); b.flush()
    else:
        soup = BeautifulSoup(rendered, 'html.parser')
        mods = soup.select('.et_pb_module')
        if mods:
            for mod in mods:
                if mod.find_parent(class_='et_pb_module'):
                    continue
                b.module(mod)
        else:
            b.walk(soup)
        b.flush()
    return '\n\n'.join(b.out)

# ---- Categorías: todo es "Actualidad"; se conserva la diferencia por si acaso ----
CAT = {3: ('noticias', 'Noticias'), 13: ('actividades', 'Actividades'), 2: ('noticias', 'Noticias'), 1: ('noticias', 'Noticias')}

def cdata(s):
    return '<![CDATA[' + s.replace(']]>', ']]]]><![CDATA[>') + ']]>'

items = []
stats = {'posts': 0, 'imgs': 0, 'galleries': 0, 'empty': []}
for p in posts:
    content = convert(p)
    # Archivos enlazados (PDF, vídeos, documentos): también se importan
    for u in re.findall(r'https?://(?:www\.)?amuma\.org/wp-content/uploads/[^"\'\s<>)]+', content):
        mid = lookup(u)
        if mid:
            used_media.setdefault(mid, p['id'])
    if p.get('featured_media') and p['featured_media'] in by_id:
        used_media.setdefault(p['featured_media'], p['id'])
    stats['posts'] += 1
    stats['galleries'] += content.count('<!-- wp:gallery')
    stats['imgs'] += content.count('<!-- wp:image')
    if len(re.sub(r'<[^>]+>|\s', '', content)) < 20:
        stats['empty'].append(p['slug'])
    cats = sorted({CAT.get(c, CAT[3]) for c in p['categories']} or {CAT[3]})
    date = p['date'].replace('T', ' ')
    gmt = p['date_gmt'].replace('T', ' ')
    meta = ''
    if p.get('featured_media') in by_id:
        meta = '<wp:postmeta><wp:meta_key>%s</wp:meta_key><wp:meta_value>%s</wp:meta_value></wp:postmeta>' % (cdata('_thumbnail_id'), cdata(str(p['featured_media'])))
    items.append('''<item>
<title>%s</title>
<link>%s</link>
<pubDate>%s</pubDate>
<dc:creator>%s</dc:creator>
<guid isPermaLink="false">https://amuma.org/?p=%d</guid>
<description></description>
<content:encoded>%s</content:encoded>
<excerpt:encoded>%s</excerpt:encoded>
<wp:post_id>%d</wp:post_id>
<wp:post_date>%s</wp:post_date>
<wp:post_date_gmt>%s</wp:post_date_gmt>
<wp:post_modified>%s</wp:post_modified>
<wp:post_modified_gmt>%s</wp:post_modified_gmt>
<wp:comment_status>closed</wp:comment_status>
<wp:ping_status>closed</wp:ping_status>
<wp:post_name>%s</wp:post_name>
<wp:status>publish</wp:status>
<wp:post_parent>0</wp:post_parent>
<wp:menu_order>0</wp:menu_order>
<wp:post_type>post</wp:post_type>
<wp:post_password></wp:post_password>
<wp:is_sticky>0</wp:is_sticky>
%s
%s
</item>''' % (
        escape(html.unescape(p['title']['rendered'])), escape(p['link']),
        datetime.fromisoformat(p['date_gmt']).replace(tzinfo=timezone.utc).strftime('%a, %d %b %Y %H:%M:%S +0000'),
        cdata('amuma'), p['id'], cdata(content), cdata(''), p['id'], date, gmt, date, gmt, p['slug'],
        '\n'.join('<category domain="category" nicename="%s">%s</category>' % (s, cdata(n)) for s, n in cats),
        meta))

att = []
for mid, parent in used_media.items():
    m = by_id[mid]
    d = m['date'].replace('T', ' ')
    att.append('''<item>
<title>%s</title>
<link>%s</link>
<pubDate></pubDate>
<dc:creator>%s</dc:creator>
<guid isPermaLink="false">%s</guid>
<description></description>
<content:encoded>%s</content:encoded>
<excerpt:encoded>%s</excerpt:encoded>
<wp:post_id>%d</wp:post_id>
<wp:post_date>%s</wp:post_date>
<wp:post_date_gmt>%s</wp:post_date_gmt>
<wp:comment_status>closed</wp:comment_status>
<wp:ping_status>closed</wp:ping_status>
<wp:post_name>%s</wp:post_name>
<wp:status>inherit</wp:status>
<wp:post_parent>%d</wp:post_parent>
<wp:menu_order>0</wp:menu_order>
<wp:post_type>attachment</wp:post_type>
<wp:post_password></wp:post_password>
<wp:is_sticky>0</wp:is_sticky>
<wp:attachment_url>%s</wp:attachment_url>
<wp:postmeta><wp:meta_key>%s</wp:meta_key><wp:meta_value>%s</wp:meta_value></wp:postmeta>
</item>''' % (escape(html.unescape(m['title']['rendered'])), escape(m['source_url']), cdata('amuma'), escape(m['source_url']),
                  cdata(''), cdata(''), mid, d, d, escape(m['slug']), parent, escape(m['source_url']),
                  cdata('_wp_attachment_image_alt'), cdata(m.get('alt_text') or '')))

xml = '''<?xml version="1.0" encoding="UTF-8" ?>
<!-- Noticias de amuma.org convertidas para el tema AMUMA. Importar en Herramientas → Importar → WordPress. -->
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
<title>AMUMA</title>
<link>https://amuma.org</link>
<description>Asociación de Cáncer de Mama y Ginecológico de Castilla-La Mancha</description>
<language>es-ES</language>
<wp:wxr_version>1.2</wp:wxr_version>
<wp:base_site_url>https://amuma.org</wp:base_site_url>
<wp:base_blog_url>https://amuma.org</wp:base_blog_url>
<wp:author><wp:author_id>1</wp:author_id><wp:author_login>%s</wp:author_login><wp:author_email>%s</wp:author_email><wp:author_display_name>%s</wp:author_display_name><wp:author_first_name>%s</wp:author_first_name><wp:author_last_name>%s</wp:author_last_name></wp:author>
<wp:category><wp:term_id>3</wp:term_id><wp:category_nicename>noticias</wp:category_nicename><wp:category_parent></wp:category_parent><wp:cat_name>%s</wp:cat_name></wp:category>
<wp:category><wp:term_id>13</wp:term_id><wp:category_nicename>actividades</wp:category_nicename><wp:category_parent></wp:category_parent><wp:cat_name>%s</wp:cat_name></wp:category>
%s
%s
</channel>
</rss>
''' % (cdata('amuma'), cdata(''), cdata('AMUMA'), cdata(''), cdata(''), cdata('Noticias'), cdata('Actividades'), '\n'.join(att), '\n'.join(items))
open(OUT, 'w').write(xml)
print(json.dumps({'posts': stats['posts'], 'attachments': len(att), 'image_blocks': stats['imgs'], 'galleries': stats['galleries'], 'casi_vacias': stats['empty']}, ensure_ascii=False))
