#!/usr/bin/env python3
"""One-off: write WebP siblings the store actually loads. Originals stay in place."""

from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1] / "public" / "assets"
QUALITY = 78


def max_edge(path: Path) -> int:
    name = path.name.lower()
    if "logo" in name or name.startswith("favicon"):
        return 480
    if name.startswith("ig"):
        return 800
    return 1600


def main() -> None:
    written = 0
    for path in sorted(ROOT.rglob("*")):
        if path.suffix.lower() not in {".png", ".jpg", ".jpeg"}:
            continue

        with Image.open(path) as image:
            has_alpha = "A" in image.getbands()
            image = image.convert("RGBA" if has_alpha else "RGB")
            image.thumbnail((max_edge(path), max_edge(path)), Image.Resampling.LANCZOS)
            dest = path.with_suffix(".webp")
            image.save(dest, "WEBP", quality=QUALITY, method=6)

        written += 1
        print(f"{path.relative_to(ROOT)} -> {dest.name} {dest.stat().st_size}")

    favicon = ROOT / "img" / "favicon_fujeira.png"
    if not favicon.exists():
        favicon = ROOT / "img" / "favicon.png"
    if favicon.exists():
        with Image.open(favicon) as image:
            image = image.convert("RGBA")
            image.thumbnail((64, 64), Image.Resampling.LANCZOS)
            small = ROOT / "img" / "favicon-site.png"
            image.save(small, "PNG", optimize=True)
            print(f"favicon-site.png {small.stat().st_size}")

    print(f"wrote {written} webp files")


if __name__ == "__main__":
    main()
