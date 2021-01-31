for file in $(find . -maxdepth 10 -name "*search_api_solr*"); do mv -v $file "${file/search_api_solr./search_api_fusion.}"; done
